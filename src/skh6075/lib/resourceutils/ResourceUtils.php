<?php

namespace skh6075\lib\resourceutils;

use InvalidArgumentException;

final class ResourceUtils{

    public const FILE_YAML = "yml";
    public const FILE_JSON = "json";

    public static function loadResource(string $path, array $default = []): array{
        $ext = pathinfo($path);
        if (!file_exists($path)) {
            switch ($ext["extension"]) {
                case self::FILE_YAML:
                    file_put_contents($path, yaml_emit($default, YAML_UTF8_ENCODING));
                    break;
                case self::FILE_JSON:
                    file_put_contents($path, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    break;
                default:
                    throw new InvalidArgumentException("This file is not supported.");
            }
        }

        $file = null;
        switch ($ext["extension"]) {
            case self::FILE_YAML:
                $file = yaml_parse(file_get_contents($path));
                break;
            case self::FILE_JSON:
                $file = json_decode(file_get_contents($path), true);
        }

        $file = $file ?? $default;
        return $file;
    }

    public static function saveResource(string $path, array $data = []): void{
        $ext = pathinfo($path);
        switch ($ext["extension"]) {
            case self::FILE_YAML:
                file_put_contents($path, yaml_emit($data));
                break;
            case self::FILE_JSON:
                file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                break;
            default:
                break;
        }
    }
}