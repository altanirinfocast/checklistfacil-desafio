<?php

namespace App\Models;

use App\Models\Cake;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = 'updated_at';

    /**
     * The name of the "updated_at at" column.
     *
     * @var string|null
     */
    const DELETED_AT = 'deleted_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nsu',
        'cake_id',
        'customer_id',
        'quantity',
        'amount',
        'created_at',
        'updated_at',
        'status'
    ];

    const STATUS_AVAILABLE = 'available';
    const STATUS_SENDED = 'sended';
    const STATUS_PENDING = 'pending';

    public function getNsuAttribute()
    {
        return '#' . Str::padLeft($this->original['nsu'], 5, '0');
    }

    public function cake()
    {
        return $this->hasOne(Cake::class, 'id', 'cake_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
