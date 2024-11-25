<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class Reminder extends Model
{
    use HasFactory;

      protected $dates = ['remind_at','event_at','created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'remind_at',
        'event_at',
        'user_id',
        'type',
        'status'
    ];


    public static $type = [
      0 => 'personal',
      1 => 'work',
      2 => 'school',
      3 => 'events'
    ];

    public function user()
  {
      return $this->belongsTo(User::class);
  }


  public function getTypeAttribute(){
      return isset(Reminder::$type[$this->attributes['type']]) ? Reminder::$type[$this->attributes['type']] : Reminder::$type[0];
  }



}
