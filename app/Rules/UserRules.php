<?php

return [
    'create' => [
        'name' => 'required|max:255',
        'email' => 'required|max:255|unique:' . $this->connection . '.users,email,' . $this->id . ',id',
        'email_verified_at' => 'nullable|date',
        'password' => 'required|max:255',
        'password_confirm' => 'required|same:password',
        'created_at' => 'nullable|date',
        'updated_at' => 'nullable|date',
        'role_id' => 'required',

    ],
    'update' => [
        'id' => '|unique:' . $this->connection . '.users,id,' . $this->id . ',id',
        'name' => 'max:255',
        'email' => 'max:255|unique:' . $this->connection . '.users,email,' . $this->id . ',id',
        'email_verified_at' => 'nullable|date',
        'password' => 'nullable|max:255',
        // 'password_confirm' => 'required|same:password',

        'created_at' => 'nullable|date',
        'updated_at' => 'nullable|date',
        'role_id' => 'required',

    ]
];
