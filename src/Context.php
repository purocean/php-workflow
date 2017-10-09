<?php

namespace Workflow;

abstract class Context
{
    public $flow;
    public $begin;
    public $end;

    abstract public function getStatus();
    abstract public function setStatus($status);
    abstract public function allowRun(Node $node);

    public function __construct(Flow $flow)
    {
        $this->flow = $flow;

        if (! $this->begin = Util::getBegin($this->flow)) {
            throw new \Exception('没有流程没有开始节点');
        }

        if (! $this->end = Util::getEnd($this->flow)) {
            throw new \Exception('没有流程没有结束节点');
        }
    }

    public function matchLine(Line $line) {
        return Util::checkCondition($line, []);
    }

    public function before(Node $currentNode, Node $nextNode)
    {
        // code
    }

    public function after(Node $currentNode, Node $nextNode)
    {
        // code
    }

    public function getAvailableNodes()
    {
        $nodes = Util::getNodesByState($this->flow, $this->getStatus(), Node::STATE_PENDDING);

        return array_filter($nodes, function (Node $node) {
            if (Util::isAutoNode($node)) { // 特殊节点直接放行
                return true;
            }

            return $this->allowRun($node);
        });
    }

    public function getCurrentNode()
    {
        if (! $nodes = $this->getAvailableNodes()) {
            throw new \Exception('无权进行流程');
        }

        return array_values($nodes)[0];
    }

    public function getNextLines(Node $node)
    {
        return array_filter(Util::getLinesByFrom($this->flow, $node), function (Line $line) {
            return $this->matchLine($line);
        });
    }

    public function checkJoin(Node $nextNode)
    {
        if ($nextNode->type === Node::TYPE_AND_JOIN) { // 检查来源节点是否已经全部完成
            return Util::prevNodesCompleted($this->flow, $nextNode, $this->getStatus());
        }

        return true;
    }

    public function go(Node $currentNode, Node $nextNode)
    {
        $status = $this->getStatus();
        $status[$currentNode->id] = Node::STATE_DONE;
        $this->setStatus($status);

        if ($currentNode->type !== Node::TYPE_BACK) {
            if ($this->checkJoin($nextNode)) { // 判断节点能不能合并，能否到达下一步
                $status[$nextNode->id] = Node::STATE_PENDDING;
            }
        } else { // 回退操作，要回退到的之前的状态
            $status = $this->backTo($nextNode, $status);
        }

        $this->setStatus($status);
    }

    public function backTo(Node $node, $status)
    {
        if (Util::isAutoNode($node)) {
            throw new \Exception('不能回退到特殊节点');
        }

        if ($node->type === Node::TYPE_BEGIN) { // 回退到发起
            return [];
        }

        $status[$node->id] = Node::STATE_PENDDING;

        foreach (Util::getChildren($this->flow, $node) as $child) {
            $status[$child->id] = Node::STATE_UNREACHABLE;
        }

        return $status;
    }

    public function launch()
    {
        if (Util::launched($this->flow, $this->getStatus())) {
            throw new \Exception('流程已发起');
        }

        $this->setStatus([$this->begin->id => Node::STATE_PENDDING]);

        $this->run();
    }

    public function finish()
    {
        $status = $this->getStatus();
        $status[$this->end->id] = Node::STATE_DONE;
        $this->setStatus($status);
    }

    public function run()
    {
        if (! Util::launched($this->flow, $this->getStatus())) {
            throw new \Exception("流程未发起");
        }

        if (Util::finished($this->flow, $this->getStatus())) {
            throw new \Exception('流程已结束');
        }

        $currentNode = $this->getCurrentNode();

        if ($currentNode->type === Node::TYPE_END) { // 走到了结束节点
            $this->finish();
            return;
        }

        $this->next($currentNode);
    }

    public function next($currentNode)
    {
        if (! $nextLines = $this->getNextLines($currentNode)) {
            throw new \Exception('下一步条件不满足');
        }

        foreach ($nextLines as $nextLine) {
            $nextNode = Util::getToNode($this->flow, $nextLine);

            try {
                $this->before($currentNode, $nextNode);
                $this->go($currentNode, $nextNode);
            } catch (\Exception $e) {
                throw $e;
            }

            $this->after($currentNode, $nextNode);

            // 特殊节点自动前行
            if (Util::isAutoNode($nextNode)) {
                if (Util::getState($nextNode, $this->getStatus()) === Node::STATE_PENDDING) {
                    $this->next($nextNode);
                }
            }

            if ($currentNode->type !== Node::TYPE_AND_SPLIT) { // 如果不是分裂节点每次运行只走一条路
                break;
            }
        }
    }
}
