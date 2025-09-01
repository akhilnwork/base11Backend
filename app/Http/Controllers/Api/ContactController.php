<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Models\ContactSubmission;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(private ContactService $contactService)
    {
    }

    /**
     * Store a new contact form submission
     */
    public function store(ContactFormRequest $request): JsonResponse
    {
        try {
            $submission = $this->contactService->handleSubmission($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you soon!',
                'data' => [
                    'id' => $submission->id,
                    'submitted_at' => $submission->created_at,
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => app()->environment('local') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
