<?php

namespace Webkul\Enclaves\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Enclaves\Contracts\CustomerAttributeValue as CustomerAttributeValueContract;

class CustomerAttributeValue extends TranslatableModel implements CustomerAttributeValueContract
{
    use HasFactory;

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    protected $translatedAttributes = [];

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'name',
        'value',
        'form_type',
        'customer_id',
        'attribute_id',
    ];
}
