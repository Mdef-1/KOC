<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTable extends Component
{
    use WithPagination;

    // Properti Model
    public $selected_id;
    public $name;

    public $password;
    public $is_active = true;

    // UI State
    public $isOpen = false;
    public $search = '';
    public $perPage = 10;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'password' => $this->selected_id
                ? 'nullable|min:6'
                : 'required|min:6',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name'      => $this->name,
            'is_active' => $this->is_active,
        ];

        // Hash password hanya jika diisi
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(
            ['id' => $this->selected_id],
            $data
        );

        session()->flash(
            'message',
            $this->selected_id ? 'User Updated.' : 'User Created.'
        );

        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->selected_id = $user->id;
        $this->name        = $user->name;
        $this->is_active   = $user->is_active;

        // Password dikosongkan (demi keamanan)
        $this->password = null;

        $this->isOpen = true;
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'User Deleted.');
    }

    private function resetInputFields()
    {
        $this->reset([
            'selected_id',
            'name',
            'password',
            'is_active',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.user-table', [
            'users' => User::where('name', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate($this->perPage),
        ]);
    }
}
