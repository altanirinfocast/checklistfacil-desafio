<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cake extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cakes';

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
        'name',
        'price',
        'weight',
        'quantity',
    ];

    public function custormers() {
        return $this->belongsToMany(Customer::class, 'orders', 'cake_id', 'customer_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'cake_id', 'id')->with('customer');
    }

    /**
     * formatando o peso para mostrar (g) de gramas
     *
     * @return string
     */
    public function getWeightAttribute() {
        $this->attributes['weight'] = $this->original['weight'].'g';
        return $this->attributes['weight'];
    }


    public function getAvailableAttribute() {
        $orders = $this->getTotalAvailable()->sum('quantity');
        $total = $this->original['quantity'] - $orders;
        $this->attributes['available'] = $total <= 0 ? 0 : $total;
        return $this->attributes['available'];
    }

    public function getTotalAvailable() {
        return $this->hasMany(Order::class,'cake_id', 'id');
    }
}
