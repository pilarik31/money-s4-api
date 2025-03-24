<?php declare(strict_types=1);

namespace Fondly\Money\Enum;

enum HttpMethod: string
{
    case Get = 'GET';
    case Head = 'HEAD';
    case Post = 'POST';
    case Put = 'PUT';
    case Delete = 'DELETE';
    case Connect = 'CONNECT';
    case Options = 'OPTIONS';
    case Trace = 'TRACE';
    case Patch = 'PATCH';

    public function getLower(): string
    {
        return strtolower($this->value);
    }
}
