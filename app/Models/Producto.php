<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'idProducto';

    const CREATED_AT = 'fechaRegistro';
    const UPDATED_AT = 'fechaActualizacion';

    /** Relación FK con empresas */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa', 'idEmpresa');
    }

    /** Relación FK con marcas */
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'idMarca', 'idMarca');
    }

    /** Relación con atributo de auditoría */
    public function editor(){
        return $this->belongsTo(Usuario::class, 'modificadoPor', 'idUsuario');
    }

    public function getAllProductos()
    {
        return Producto::with(['empresa','marca','editor'])->orderBy('idProducto','ASC')->get();
    }
    
    public function getProducto($idProducto)
    {
        return Producto::with(['empresa','marca','editor'])->find($idProducto);
    }

    public function getAllProductosGroupByNombreProducto()
    {
        return Producto::groupBy('nombreProducto')->orderBy('nombreProducto')->get();
    }
}
