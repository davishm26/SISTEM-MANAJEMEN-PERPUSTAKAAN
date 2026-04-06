<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        return MemberResource::collection($query->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'member_code' => 'required|string|max:20|unique:members,member_code',
            'email'       => 'required|email|unique:members,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'status'      => 'nullable|in:active,inactive,suspended',
            'joined_at'   => 'nullable|date',
        ]);

        $member = Member::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Member berhasil didaftarkan.',
            'data'    => new MemberResource($member),
        ], 201);
    }

    public function show(string $id)
    {
        $member = Member::find($id);
        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => new MemberResource($member)]);
    }

    // TUGAS BAB 6: Update Member
    public function update(Request $request, string $id)
    {
        $member = Member::find($id);
        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:150',
            'member_code' => 'sometimes|required|string|max:20|unique:members,member_code,' . $id,
            'email'       => 'sometimes|required|email|unique:members,email,' . $id,
            'status'      => 'sometimes|required|in:active,inactive,suspended',
        ]);

        $member->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Data member berhasil diperbarui.',
            'data'    => new MemberResource($member)
        ], 200);
    }

    // TUGAS BAB 6: Delete Member
    public function destroy(string $id)
    {
        $member = Member::find($id);
        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Member tidak ditemukan.'], 404);
        }

        // Cek status sebelum hapus
        if ($member->status === 'active') {
            return response()->json([
                'success' => false,
                'message' => "Member '{$member->name}' tidak bisa dihapus karena status masih aktif."
            ], 422);
        }

        $name = $member->name;
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => "Member '{$name}' berhasil dihapus."
        ], 200);
    }
}
