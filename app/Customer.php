<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * This tells Laravel to save data only on this fields
     */
    protected $fillable = [
        'name',
        'company_id',
        'email',
        'phone',
        'address',
        'active'
    ];

    /**
     * Sets this model's attributes default value.
     * This `Model` might be null but the attributes set
     * here will maintain their default values for use.
     */
    protected $attributes = [
        'active' => 1
    ];

    /**
     * Gets the values we need in place of the default value of this attribute
     * in this case the `active` & `inactive` attributes which defaults to `0` & `1`
     * but would return `active` & `inactive` respectively instead.
     */
    public function getActiveAttribute($attribute) {
        return $this->activeOptions()[$attribute];
    }

    /**
     * An Eloquent scope function to retrieve only customer
     * that are active using the Eloquent `where` clause
     */
    public function scopeActive($query) {
        return $query->where('active', 1);
    }

    /**
     * An Eloquent scope function to retrieve only customer
     * that are inactive using the Eloquent `where` clause
     */
    public function scopeInactive($query) {
        return $query->where('active', 0);
    }

    /**
     *An Eloquent function that relates a customer to its company using the Laravel Eloquent's `belongsTo()` method
     */
    public function company () {
        return $this->belongsTo(Company::class);
    }

    public function activeOptions () {
        return [
            1 => 'Active',
            0 => 'Inactive'
        ];
    }


}
