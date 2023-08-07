<?php

namespace app\common\snowflake;

interface SequenceResolver
{
    /**
     * The snowflake.
     *
     * @param  int|string  $currentTime current timestamp: milliseconds
     * @return int
     */
    public function sequence(int $currentTime);
}
