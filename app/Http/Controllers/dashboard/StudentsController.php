<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\StudentImage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadministrator|administrator');
        $this->middleware('permission:students-read')->only('index', 'show');
        $this->middleware('permission:students-create')->only('create', 'store');
        $this->middleware('permission:students-update')->only('edit', 'update');
        $this->middleware('permission:students-delete|students-trash')->only('destroy', 'trashed');
        $this->middleware('permission:students-restore')->only('restore');
    }

    /**
     * Ensure directory exists with proper permissions
     */
    private function ensureDirectoryExists($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
            chmod($path, 0755);
            Log::info('Created directory: ' . $path);
        }
        return $path;
    }

    public function index()
    {
        $students = Student::with(['user', 'class', 'images'])
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(100);

        return view('dashboard.students.index')->with('students', $students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = ClassModel::all();
        return view('dashboard.students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string|max:255",
            'email' => "required|string|email|max:255|unique:users",
            'phone' => "required|string|unique:users",
            'gender' => "required|in:male,female",
            'age' => "required|integer|min:1|max:100",
            'date_of_join' => "required|date",
            'date_of_birth' => "required|date|before:today",
            'images' => "nullable|array",
            'images.*' => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            'class_id' => "required|integer|exists:classes,id",
            'fees_of_uniform' => "nullable|integer|min:0",
            'fees_of_book' => "nullable|integer|min:0",
            'profile' => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        try {
            // Ensure storage directories exist
            $storagePath = storage_path('app/public/images');
            $publicStoragePath = public_path('storage/images');
            
            $this->ensureDirectoryExists($storagePath);
            $this->ensureDirectoryExists($storagePath . '/users');
            $this->ensureDirectoryExists($storagePath . '/students');
            
            // Also ensure public/storage symlink directories exist
            $this->ensureDirectoryExists($publicStoragePath);
            $this->ensureDirectoryExists($publicStoragePath . '/users');
            $this->ensureDirectoryExists($publicStoragePath . '/students');

            Log::info('Creating student with images', [
                'has_images' => $request->hasFile('images'),
                'images_count' => $request->file('images') ? count($request->file('images')) : 0,
                'storage_path' => $storagePath,
                'public_storage_path' => $publicStoragePath
            ]);

            // Handle profile image
            $profile = null;
            if ($request->hasFile('profile')) {
                try {
                    $profileFile = $request->file('profile');
                    $profileName = time() . '_' . uniqid() . '.' . $profileFile->getClientOriginalExtension();
                    
                    // Save to storage/app/public/images/users
                    $profilePath = $storagePath . '/users/' . $profileName;
                    Image::make($profileFile)
                        ->resize(300, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save($profilePath, 80);
                    
                    // Also save to public/storage/images/users if different
                    if ($storagePath !== dirname($publicStoragePath . '/users')) {
                        $publicProfilePath = $publicStoragePath . '/users/' . $profileName;
                        Image::make($profileFile)
                            ->resize(300, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })
                            ->save($publicProfilePath, 80);
                    }
                    
                    $profile = $profileName;
                    Log::info('Profile image saved: ' . $profileName);
                    
                } catch (\Exception $e) {
                    Log::error('Error saving profile image: ' . $e->getMessage());
                    $profile = $request->gender === 'male' ? 'avatarmale.png' : 'avatarfemale.png';
                }
            } else {
                $profile = $request->gender === 'male' ? 'avatarmale.png' : 'avatarfemale.png';
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->phone),
                'phone' => $request->phone,
                'gender' => $request->gender,
                'profile' => $profile,
                'phone_verified_at' => now(),
            ]);

            Log::info('User created with ID: ' . $user->id);

            // Create student
            $student = Student::create([
                'user_id' => $user->id,
                'age' => $request->age,
                'date_of_join' => $request->date_of_join,
                'date_of_birth' => $request->date_of_birth,
                'fees_of_uniform' => $request->fees_of_uniform ?? 0,
                'fees_of_book' => $request->fees_of_book ?? 0,
                'class_id' => $request->class_id,
            ]);

            Log::info('Student created with ID: ' . $student->id);

            // Handle multiple images
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                Log::info('Processing ' . count($files) . ' image files');

                foreach ($files as $index => $file) {
                    if ($file && $file->isValid()) {
                        try {
                            $imageName = time() . '_' . uniqid() . '_' . $index . '.' . $file->getClientOriginalExtension();
                            
                            Log::info('Processing image ' . ($index + 1), [
                                'original_name' => $file->getClientOriginalName(),
                                'new_name' => $imageName,
                                'size' => $file->getSize()
                            ]);
                            
                            // Save to storage/app/public/images/students
                            $imagePath = $storagePath . '/students/' . $imageName;
                            Image::make($file)->save($imagePath, 80);
                            
                            // Also save to public/storage/images/students if different
                            if ($storagePath !== dirname($publicStoragePath . '/students')) {
                                $publicImagePath = $publicStoragePath . '/students/' . $imageName;
                                Image::make($file)->save($publicImagePath, 80);
                            }
                            
                            // Create database record
                            $studentImage = StudentImage::create([
                                'student_id' => $student->id,
                                'image' => $imageName,
                            ]);
                            
                            Log::info('StudentImage record created', [
                                'id' => $studentImage->id,
                                'student_id' => $student->id,
                                'image' => $imageName
                            ]);
                            
                        } catch (\Exception $e) {
                            Log::error('Error saving image ' . $index . ': ' . $e->getMessage());
                        }
                    } else {
                        Log::warning('Invalid file at index ' . $index);
                    }
                }

                // Verify images were saved
                $savedImagesCount = StudentImage::where('student_id', $student->id)->count();
                Log::info('Total images saved in database: ' . $savedImagesCount);
            } else {
                Log::info('No images uploaded for this student');
            }

            // Assign student role
            $role = Role::firstOrCreate(
                ['name' => 'student'],
                [
                    'display_name' => 'Student',
                    'description' => 'Student role',
                ]
            );

            $user->addRoles(['student']);

            alertSuccess('Student created successfully', 'تم إضافة الطالب بنجاح');
            return redirect()->route('students.index');
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while creating student: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            alertError('Database error occurred: ' . $e->getMessage(), 'حدث خطأ في قاعدة البيانات');
            return redirect()->back()->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error creating student: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            alertError('Something went wrong: ' . $e->getMessage(), 'حدث خطأ أثناء إنشاء الطالب');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $classes = ClassModel::all();
        return view('dashboard.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => "required|string|max:255",
            'email' => "required|string|email|max:255|unique:users,email," . $student->user_id,
            'phone' => "required|string|unique:users,phone," . $student->user_id,
            'gender' => "required|in:male,female",
            'age' => "required|integer|min:1|max:100",
            'date_of_join' => "required|date",
            'date_of_birth' => "required|date|before:today",
            'fees_of_uniform' => "nullable|integer|min:0",
            'fees_of_book' => "nullable|integer|min:0",
            'images' => "nullable|array",
            'images.*' => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            'class_id' => "required|integer|exists:classes,id",
            'profile' => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        try {
            // Ensure storage directories exist
            $storagePath = storage_path('app/public/images');
            $publicStoragePath = public_path('storage/images');
            
            $this->ensureDirectoryExists($storagePath . '/users');
            $this->ensureDirectoryExists($storagePath . '/students');
            $this->ensureDirectoryExists($publicStoragePath . '/users');
            $this->ensureDirectoryExists($publicStoragePath . '/students');

            // Handle profile image
            $profile = $student->user->profile ?? ($request->gender === 'male' ? 'avatarmale.png' : 'avatarfemale.png');
            
            if ($request->hasFile('profile')) {
                try {
                    $profileFile = $request->file('profile');
                    $profileName = time() . '_' . uniqid() . '.' . $profileFile->getClientOriginalExtension();
                    
                    // Save to both locations
                    $profilePath = $storagePath . '/users/' . $profileName;
                    Image::make($profileFile)
                        ->resize(300, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save($profilePath, 80);
                    
                    if ($storagePath !== dirname($publicStoragePath . '/users')) {
                        $publicProfilePath = $publicStoragePath . '/users/' . $profileName;
                        Image::make($profileFile)
                            ->resize(300, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })
                            ->save($publicProfilePath, 80);
                    }
                    
                    $profile = $profileName;
                    Log::info('Profile image updated: ' . $profileName);
                    
                } catch (\Exception $e) {
                    Log::error('Error updating profile image: ' . $e->getMessage());
                }
            }

            // Update user
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'profile' => $profile,
            ]);

            // Update student
            $student->update([
                'age' => $request->age,
                'date_of_join' => $request->date_of_join,
                'date_of_birth' => $request->date_of_birth,
                'fees_of_uniform' => $request->fees_of_uniform ?? 0,
                'fees_of_book' => $request->fees_of_book ?? 0,
                'class_id' => $request->class_id,
            ]);

            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    if ($file->isValid()) {
                        try {
                            $imageName = time() . '_' . uniqid() . '_' . $index . '.' . $file->getClientOriginalExtension();
                            
                            // Save to both locations
                            $imagePath = $storagePath . '/students/' . $imageName;
                            Image::make($file)->save($imagePath, 80);
                            
                            if ($storagePath !== dirname($publicStoragePath . '/students')) {
                                $publicImagePath = $publicStoragePath . '/students/' . $imageName;
                                Image::make($file)->save($publicImagePath, 80);
                            }
                            
                            StudentImage::create([
                                'student_id' => $student->id,
                                'image' => $imageName,
                            ]);
                            
                            Log::info('New student image added: ' . $imageName);
                            
                        } catch (\Exception $e) {
                            Log::error('Error saving new image: ' . $e->getMessage());
                        }
                    }
                }
            }

            alertSuccess('Student updated successfully', 'تم تعديل الطالب بنجاح');
            return redirect()->route('students.index');
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating student: ' . $e->getMessage());
            alertError('Database error occurred', 'حدث خطأ في قاعدة البيانات');
            return redirect()->back()->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            alertError('Something went wrong while updating student', 'حدث خطأ أثناء تحديث الطالب');
            return redirect()->back()->withInput();
        }
    }
}