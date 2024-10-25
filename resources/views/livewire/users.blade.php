<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if ($updateMode)
        @include('livewire.update')
    @else
        @include('livewire.create')
    @endif

    <table class="min-w-full table-auto mt-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Apellido</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Teléfono</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="bg-white border-b">
                    <td class="px-4 py-2">{{ $user->id }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->apellido }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->telefono }}</td>

                    <td class="px-4 py-2">
                        @if ($user->activo)
                            <button wire:click="edit({{ $user->id }})"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Editar</button>
                            <button wire:click="delete({{ $user->id }})"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Inactivar</button>
                        @else
                            <button wire:click="reactivate({{ $user->id }})"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">Reactivar</button>
                        @endif

                        <!-- Botón de eliminación permanente -->
                        <button wire:click="destroy({{ $user->id }})"
                            class="bg-red-800 hover:bg-red-900 text-white font-bold py-1 px-3 rounded mt-2">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
