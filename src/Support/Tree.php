<?php

// +----------------------------------------------------------------------
// | AIAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014~2023 https://luomor.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/azure-dragon-ai/azure-dragon-ai/blob/main/LICENSE )
// +----------------------------------------------------------------------
// | Author: PeterZhang [ zhangchunsheng423@gmail.com ]
// +----------------------------------------------------------------------


declare(strict_types=1);

namespace AI\Support;

class Tree
{
    protected static string $pk = 'id';

    /**
     *
     * @param string $pk
     * @return Tree
     */
    public static function setPk(string $pk): Tree
    {
        self::$pk = $pk;

        return new self();
    }

    /**
     * return done
     *
     * @param array $items
     * @param int $pid
     * @param string $pidField
     * @param string $child
     * @return array
     */
    public static function done(array $items, int $pid = 0, string $pidField = 'parent_id', string $child = 'children'): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item[$pidField] == $pid) {
                $children = self::done($items, $item[self::$pk], $pidField, $child);

                if (count($children)) {
                    $item[$child] = $children;
                }

                $tree[] = $item;
            }
        }

        return $tree;
    }
}
