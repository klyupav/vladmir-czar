<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcessParsingLog
 */
class ProcessParsingLog extends Model
{
    protected $table = 'process_parsing_log';

    public $timestamps = false;

    protected $fillable = [
        'process',
        'begin',
        'end',
        'donor_class_name',
        'source',
        'write_begin',
        'write_end',
        'message'
    ];

    protected $guarded = [];

        
}