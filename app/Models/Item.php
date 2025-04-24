<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // nama tabel untuk Model Item ini
    protected $table = 'items';

    // nama primary key
    protected $primaryKey = 'id';

    // tipe data untuk primary key
    protected $keyType = 'int';

    protected $fillable = ['item_name', 'status']; // yang boleh di isi, lainnya tidak boleh
}
