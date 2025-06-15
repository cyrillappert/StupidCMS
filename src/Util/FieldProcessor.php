<?php

declare(strict_types=1);

namespace StupidCMS\Util;

class FieldProcessor
{
    public function sanitize(array $fields): array
    {
        $sanitized = [];
        foreach ($fields as $key => $value) {
            $sanitized[$key] = is_string($value) 
                ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8')
                : $value;
        }
        return $sanitized;
    }

    public function addDefaults(array $fields, string $slug): array
    {
        $fields['title'] ??= ucfirst($slug);
        $fields['date'] ??= (new \DateTime())->format('Y-m-d');
        return $fields;
    }

    public function extractKnown(array $meta, array $knownFields): array
    {
        return array_diff_key($meta, array_flip($knownFields));
    }
}