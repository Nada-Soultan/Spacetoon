@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Edit student') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('students.update', ['student' => $student->id]) }}"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="name">{{ __('Name') }}</label>
                                <input name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $student->user->name) }}" type="text" autocomplete="on" 
                                    id="name" autofocus required />
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">{{ __('Email') }}</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    id="email" name="email" autocomplete="on" 
                                    value="{{ old('email', $student->user->email) }}">
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="phone">{{ __('Phone') }}</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="number"
                                    autocomplete="on" id="phone" name="phone" 
                                    value="{{ old('phone', $student->user->phone) }}" required />
                                @error('phone')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="gender">{{ __('Gender') }}</label>
                                <br>
                                <div class="form-check form-check-inline">
                                    <input {{ old('gender', $student->user->gender) == 'male' ? 'checked' : '' }}
                                        class="form-check-input @error('gender') is-invalid @enderror" id="gender1"
                                        type="radio" name="gender" value="male" required />
                                    <label class="form-check-label" for="gender1">{{ __('Male') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input {{ old('gender', $student->user->gender) == 'female' ? 'checked' : '' }}
                                        class="form-check-input @error('gender') is-invalid @enderror" id="gender2"
                                        type="radio" name="gender" value="female" required />
                                    <label class="form-check-label" for="gender2">{{ __('Female') }}</label>
                                </div>
                                @error('gender')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="age">{{ __('Age') }}</label>
                                <input class="form-control @error('age') is-invalid @enderror" type="number"
                                    autocomplete="on" id="age" name="age" 
                                    value="{{ old('age', $student->age) }}" required />
                                @error('age')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row gx-2">
                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="date_of_join">{{ __('Date Of Join') }}</label>
                                    <input class="form-control @error('date_of_join') is-invalid @enderror" type="date"
                                        autocomplete="on" id="date_of_join" name="date_of_join"
                                        value="{{ old('date_of_join', $student->date_of_join) }}" required />
                                    @error('date_of_join')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="date_of_birth">{{ __('Date Of Birth') }}</label>
                                    <input class="form-control @error('date_of_birth') is-invalid @enderror" type="date"
                                        autocomplete="on" id="date_of_birth" name="date_of_birth"
                                        value="{{ old('date_of_birth', $student->date_of_birth) }}" required />
                                    @error('date_of_birth')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row gx-2">
                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="fees_of_uniform">{{ __('Fees Of Uniform') }}</label>
                                    <input class="form-control @error('fees_of_uniform') is-invalid @enderror"
                                        type="number" autocomplete="on" id="fees_of_uniform" name="fees_of_uniform"
                                        value="{{ old('fees_of_uniform', $student->fees_of_uniform) }}" />
                                    @error('fees_of_uniform')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="fees_of_book">{{ __('Fees Of Book') }}</label>
                                    <input class="form-control @error('fees_of_book') is-invalid @enderror" type="number"
                                        autocomplete="on" id="fees_of_book" name="fees_of_book"
                                        value="{{ old('fees_of_book', $student->fees_of_book) }}" />
                                    @error('fees_of_book')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Existing Images Display -->
                            @if ($student->images && $student->images->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Existing Images') }} ({{ $student->images->count() }})</label>
                                    <div class="row g-2">
                                        @foreach ($student->images as $image)
                                            <div class="col-md-3 col-sm-4" id="image-container-{{ $image->id }}">
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/images/students/' . $image->image) }}"
                                                        class="img-thumbnail" style="width:100%; height: 150px; object-fit: cover;"
                                                        onerror="this.src='{{ asset('storage/images/placeholder.png') }}'">
                                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                                        onclick="deleteImage({{ $image->id }})" title="{{ __('Delete Image') }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted d-block mt-1 text-center">
                                                    {{ Str::limit($image->image, 15) }}
                                                </small>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ __('No existing images for this student') }}
                                    </div>
                                </div>
                            @endif

                            <!-- Add New Images -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('Add New Images') }}</label>
                                <div id="image-upload-container">
                                    <div class="image-upload-row mb-2">
                                        <div class="input-group">
                                            <input name="images[]"
                                                class="form-control @error('images') is-invalid @enderror" type="file"
                                                accept="image/*" />
                                            <button type="button" class="btn btn-success add-image-btn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @error('images')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="class_id" class="form-label">{{ __('Class') }}</label>
                                <select id="class_id" class="form-control @error('class_id') is-invalid @enderror"
                                    name="class_id" required>
                                    <option value="">{{ __('Select Class') }}</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="profile">{{ __('Profile Picture') }}</label>
                                <input name="profile" class="img form-control @error('profile') is-invalid @enderror"
                                    type="file" id="profile" accept="image/*" />
                                <small class="form-text text-muted">{{ __('Leave empty to keep current profile picture') }}</small>
                                @error('profile')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Current Profile Picture') }}</label>
                                <div class="col-md-10">
                                    @php
                                        $profilePic = $student->user->profile 
                                            ? asset('storage/images/users/' . $student->user->profile) 
                                            : ($student->user->gender == 'male' 
                                                ? asset('assets/img/avatar/avatarmale.png') 
                                                : asset('assets/img/avatar/avatarfemale.png'));
                                    @endphp
                                    <img src="{{ $profilePic }}"
                                        style="width:100px; border: 1px solid #999" 
                                        class="img-thumbnail img-prev"
                                        onerror="this.src='{{ asset('assets/img/avatar/avatarmale.png') }}'">
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Update Student') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('image-upload-container');

            // Event delegation for add/remove buttons
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-image-btn') || e.target.closest('.add-image-btn')) {
                    e.preventDefault();
                    addImageRow();
                }

                if (e.target.classList.contains('remove-image-btn') || e.target.closest('.remove-image-btn')) {
                    e.preventDefault();
                    const row = e.target.closest('.image-upload-row');
                    row.remove();
                    updateButtons();
                }
            });

            function addImageRow() {
                const newRow = document.createElement('div');
                newRow.className = 'image-upload-row mb-2';
                newRow.innerHTML = `
                    <div class="input-group">
                        <input name="images[]" class="form-control" type="file" accept="image/*" />
                        <button type="button" class="btn btn-success add-image-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger remove-image-btn">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newRow);
                updateButtons();
            }

            function updateButtons() {
                const rows = container.querySelectorAll('.image-upload-row');
                rows.forEach((row, index) => {
                    const addBtn = row.querySelector('.add-image-btn');
                    const removeBtn = row.querySelector('.remove-image-btn');

                    // Show add button only on last row
                    if (index === rows.length - 1) {
                        addBtn.style.display = 'block';
                    } else {
                        addBtn.style.display = 'none';
                    }

                    // Show remove button only if more than one row
                    if (rows.length > 1) {
                        if (!removeBtn) {
                            const btnGroup = row.querySelector('.input-group');
                            const newRemoveBtn = document.createElement('button');
                            newRemoveBtn.type = 'button';
                            newRemoveBtn.className = 'btn btn-danger remove-image-btn';
                            newRemoveBtn.innerHTML = '<i class="fas fa-minus"></i>';
                            btnGroup.appendChild(newRemoveBtn);
                        }
                    }
                });
            }

            updateButtons();
        });

        // Delete existing image function
        function deleteImage(imageId) {
            if (confirm('{{ __("Are you sure you want to delete this image?") }}')) {
                // Add hidden input to mark for deletion
                const form = document.querySelector('form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = imageId;
                form.appendChild(input);
                
                // Hide the image container
                const container = document.getElementById('image-container-' + imageId);
                if (container) {
                    container.style.display = 'none';
                }
            }
        }
    </script>

    <style>
        .image-upload-row .input-group {
            display: flex;
        }

        .image-upload-row .btn {
            white-space: nowrap;
        }

        .add-image-btn,
        .remove-image-btn {
            min-width: 45px;
        }
    </style>
@endsection