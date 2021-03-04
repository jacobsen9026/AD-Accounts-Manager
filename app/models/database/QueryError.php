<?php


namespace App\Models\Database;


class QueryError
{
    const NO_SUCH_COLUMN = 'no such column';
    const NO_SUCH_TABLE = 'no such table';
    const UNRECOGNIZED_TOKEN = 'unrecognized token';
    const SYNTAX_ERROR = 'syntax error';
}