<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{

    const JANUARY='01';
    const FEBRUARY='02';
    const MARCH='03';
    const APRIL='04';
    const MAY='05';
    const JUNE='06';
    const JULY='07';
    const AUGUST='08';
    const SEPTEMBER='09';
    const OCTOBER='10';
    const NOVEMBER='11';
    const DECEMBER='12';
    public  static function createdAt($month){
        if ($month === self::JANUARY){
            return 'January';
        }elseif ($month === self::FEBRUARY){
            return 'February';
        }elseif ($month === self::MARCH){
            return 'March';
        }
        elseif ($month === self::APRIL){
            return 'April';
        }elseif ($month === self::MAY){
            return 'May';
        }elseif ($month === self::JUNE){
            return 'June';
        }
        elseif ($month === self::JULY){
            return 'July';
        }elseif ($month === self::AUGUST){
            return 'August';
        }elseif ($month === self::SEPTEMBER){
            return 'September';
        }
        elseif ($month === self::OCTOBER){
            return 'October';
        }elseif ($month === self::NOVEMBER){
            return 'November';
        }elseif ($month === self::DECEMBER){
            return 'December';
        }
        else{
            return 'Invalid';
        }

    }
    public function getExpensesAttribute(){
        return Payments::all();
    }
}
