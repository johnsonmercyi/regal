<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    /**
     * This tells Laravel to save data only on this fields
     */
    protected $fillable = [
        'name',
        'email',
        'phone'
    ];

    /**
     *An Eloquent function that relates a company to its costomers using the Laravel Eloquent's `belongsTo()` method
     */
    public function customers() {
        return $this->hasMany(Customer::class);
    }


}
