<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithPagination;

    public $name, $apellido, $email, $telefono, $password, $user_id;
    public $updateMode = false;
    public $perPage = 10; // Número de usuarios por página

    /**
     * Renderizar la vista del componente con los usuarios paginados.
     */
    public function render()
    {
        $users = User::paginate($this->perPage); // Mostrar todos los usuarios
        return view('livewire.users', ['users' => $users]);
    }

    /**
     * Limpiar los campos del formulario.
     */
    private function resetInputFields()
    {
        $this->name = '';
        $this->apellido = '';
        $this->email = '';
        $this->telefono = '';
        $this->password = '';
        $this->user_id = null;
    }

    /**
     * Almacenar un nuevo usuario con rol de administrador.
     */
    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|numeric',
            'password' => 'required|min:6',
        ]);

        // Encriptar la contraseña antes de guardar
        $validatedData['password'] = Hash::make($this->password);

        // Crear el usuario
        $user = User::create($validatedData);

        // Asignar el rol "admin" al usuario creado
        $user->assignRole('admin');

        session()->flash('message', 'Administrador creado exitosamente.');

        $this->resetInputFields();  // Limpiar los campos del formulario
    }

    /**
     * Editar un usuario existente.
     */
    public function edit($id)
    {
        // Buscar el usuario por su ID
        $user = User::findOrFail($id);

        // Rellenar el formulario con los datos del usuario seleccionado
        $this->user_id = $id;
        $this->name = $user->name;
        $this->apellido = $user->apellido;
        $this->email = $user->email;
        $this->telefono = $user->telefono;

        // Cambiar a modo de actualización
        $this->updateMode = true;
    }

    /**
     * Actualizar el usuario editado.
     */
    public function update()
    {
        // Validar los datos del formulario
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user_id, // Permitir el mismo email del usuario actual
            'telefono' => 'nullable|numeric',
        ]);

        // Si hay una nueva contraseña, encriptarla
        if (!empty($this->password)) {
            $validatedData['password'] = Hash::make($this->password);
        }

        // Buscar y actualizar el usuario
        $user = User::find($this->user_id);
        $user->update($validatedData);

        session()->flash('message', 'Usuario actualizado exitosamente.');

        $this->resetInputFields();
        $this->updateMode = false;
    }

    /**
     * Inactivar un usuario.
     */
    public function delete($id)
    {
        $user = User::find($id);
        $user->update(['activo' => false]);

        session()->flash('message', 'Usuario inactivado exitosamente.');
    }

    /**
     * Reactivar un usuario.
     */
    public function reactivate($id)
    {
        $user = User::find($id);
        $user->update(['activo' => true]);

        session()->flash('message', 'Usuario reactivado exitosamente.');
    }

    /**
     * Cancelar la edición.
     */
    public function cancel()
    {
        $this->resetInputFields();
        $this->updateMode = false;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        session()->flash('message', 'Usuario eliminado permanentemente.');
    }
}
