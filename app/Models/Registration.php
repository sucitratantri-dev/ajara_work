<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Registration extends Model {protected $fillable=['user_id','competition_id','status','notes','phone','university','study_program','participant_type','team_name','members','document_path'];public function user():BelongsTo{return $this->belongsTo(User::class);}public function competition():BelongsTo{return $this->belongsTo(Competition::class);}}
