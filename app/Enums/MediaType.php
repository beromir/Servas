<?php

namespace App\Enums;

enum MediaType: string
{
    case Text = 'text';
    case Audio = 'audio';
    case Video = 'video';
    case Image = 'image';

    public static function getDefaultValue(): string
    {
        return self::Text->value;
    }

    public static function createFromString(string $value): MediaType|null
    {
        foreach (MediaType::cases() as $mediaType) {
            if ($value === $mediaType->value) {

                return $mediaType;
            }
        }

        return null;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => 'Text',
            self::Audio => 'Audio',
            self::Video => 'Video',
            self::Image => 'Image',
        };
    }

    public static function getMediaTypesAsArray(): array
    {
        $mediaTypes = [];

        foreach (self::cases() as $case) {
            $mediaTypes[] = (object)[
                'label' => $case->getLabel(),
                'value' => $case->value,
            ];
        }

        return $mediaTypes;
    }
}
