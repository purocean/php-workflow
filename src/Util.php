<?php

namespace Workflow;

class Util
{
    public static function isAutoNode(Node $node)
    {
        return (! in_array($node->type, [Node::TYPE_NORMAL, Node::TYPE_BEGIN]));
    }

    public static function getState(Node $node, $status)
    {
        $state = $status[$node->id] ?? 0;

        return in_array($state, [Node::STATE_UNREACHABLE, Node::STATE_PENDDING, Node::STATE_DONE]) ? $state : 0;
    }

    public static function launched(Flow $flow, $status)
    {
        return in_array(self::getState(self::getBegin($flow), $status), [Node::STATE_PENDDING, Node::STATE_DONE]);
    }

    public static function finished(Flow $flow, $status)
    {
        return self::getState(self::getEnd($flow), $status) === Node::STATE_DONE;
    }

    public static function getNodesByState(Flow $flow, $status, $state)
    {
        return array_filter($flow->nodes, function (Node $node) use ($status, $state) {
            return self::getState($node, $status) === $state;
        });
    }

    public static function checkCondition(Line $line, $vars = [])
    {
        if (is_string($line->condition)) {
            try {
                $vars = (array) $vars;
                extract($vars);
                return eval("return (int)({$line->condition});") === 1;
            } catch (\Exception $e) {
                throw new \Exception('条件判断语句不正确');
            }
        } elseif ($line->condition instanceof \Closure) {
            $call = $line->condition;
            return $call($vars);
        }

        return false;
    }

    public static function getChildren(Flow $flow, Node $node)
    {
        $nodes = [];

        foreach (self::getLinesByFrom($flow, $node) as $line) {
            $nextNode = self::getToNode($flow, $line);
            $nodes[$nextNode->id] = $nextNode;

            if (! in_array($nextNode->type, [Node::TYPE_BACK, Node::TYPE_END])) {
                $nodes = array_merge($nodes, self::getChildren($flow, $nextNode));
            }
        }

        return $nodes;
    }

    public static function getToNode(Flow $flow, Line $nextLine)
    {
        if (! $node = self::getNodeById($flow, $nextLine->to)) {
            throw new \Exception('连线末端节点不正确');
        }

        return $node;
    }

    public static function getLineById(Flow $flow, $id)
    {
        return $flow->lines[$id] ?? null;
    }

    public static function getNodeById(Flow $flow, $id)
    {
        return $flow->nodes[$id] ?? null;
    }

    public static function getNodesByIds(Flow $flow, $ids)
    {
        return array_intersect_key($flow->nodes, array_flip($ids));
    }

    public static function getNodesByType(Flow $flow, $type)
    {
        return array_filter($flow->nodes, function (Node $node) use ($type) {
            return $node->type === $type;
        });
    }

    public static function getBegin(Flow $flow)
    {
        return array_values(self::getNodesByType($flow, Node::TYPE_BEGIN))[0] ?? null;
    }

    public static function getEnd(Flow $flow)
    {
        return array_values(self::getNodesByType($flow, Node::TYPE_END))[0] ?? null;
    }

    public static function getLinesByFrom(Flow $flow, Node $from)
    {
        return array_filter($flow->lines, function (Line $line) use ($from) {
            return $line->from === $from->id;
        });
    }

    public static function getLinesByTo(Flow $flow, Node $to)
    {
        return array_filter($flow->lines, function (Line $line) use ($to) {
            return $line->to === $to->id;
        });
    }

    public static function prevNodesCompleted(Flow $flow, Node $node, $status)
    {
        foreach (self::getLinesByTo($flow, $node) as $line) {
            if (! isset($status[$line->from]) or $status[$line->from] !== Node::STATE_DONE) {
                return false;
            }
        }

        return true;
    }
}
