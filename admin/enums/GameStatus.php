<?php

namespace admin\enums;

use common\enums\{DictionaryInterface, DictionaryTrait};

enum GameStatus: int implements DictionaryInterface
{
    use DictionaryTrait;

    case InProcess = 0;
    case Abandon = 10;
    case Finished = 20;

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return match ($this) {
            self::InProcess => 'В процессе',
            self::Abandon => 'Брошена',
            self::Finished => 'Закончена',
        };
    }

    /**
     * {@inheritdoc}
     */
    public function color(): string
    {
        return match ($this) {
            self::InProcess => 'var(--bs-body-color)',
            self::Abandon => 'var(--bs-danger)',
            self::Finished => 'var(--bs-success)',
        };
    }
}
