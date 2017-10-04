<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcessParsingLog
 *
 * @property int $id
 * @property string $process
 * @property string $begin
 * @property string $end
 * @property string $donor_class_name
 * @property string $source
 * @property string $write_begin
 * @property string $write_end
 * @property string $message
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereBegin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereDonorClassName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereProcess($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereSource($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereWriteBegin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProcessParsingLog whereWriteEnd($value)
 * @mixin \Eloquent
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