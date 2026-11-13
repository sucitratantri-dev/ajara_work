<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PublicationOrder extends Model {protected $fillable=['user_id','competition_id','package','amount','proof_path','payment_status','review_status','admin_note','paid_at'];protected $casts=['paid_at'=>'datetime'];public function user():BelongsTo{return $this->belongsTo(User::class);}public function competition():BelongsTo{return $this->belongsTo(Competition::class);}}
