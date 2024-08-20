<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Categories;
use App\Models\Tags;
use App\Models\User;
use App\Models\Posts;

class AdminController extends Controller
{

    // category master
    public function categories(Request $request)
    {
        if (Auth::check()) {
            if ($request->ajax()) {
                $categories =  Categories::leftJoin('categories AS parent', 'categories.parent_category', '=', 'parent.id')
                    ->select('categories.id', 'categories.category_name', 'parent.category_name AS parent_category_name', 'categories.created_at', 'categories.updated_at', 'categories.status')
                    ->get();
                return datatables()->of($categories)->toJson();
            }
            $data = Categories::get()->where('parent_category', 0);
            return view('categories', compact('data'));
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function addCategory(Request $request)
    {
        $id = $request->categoryId;

        $validator = Validator::make($request->all(), [
            'categoryName' => 'required|unique:categories,category_name'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = ($id == null) ? new Categories : Categories::find($id);

        $category->parent_category = $request->parentCategory;
        $category->category_name = $request->categoryName;
        $category->save();

        if ($id == null) {
            return response()->json([
                'status' => 200,
                'message' => 'Category Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Category Updated Successfully'
            ]);
        }
    }

    public function editCategory($id)
    {
        $category = Categories::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Category Not Found"
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $deleteCategory = Categories::find($id)->delete();
        if ($deleteCategory) {
            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Failed to delete Category Data',
            ]);
        }
    }

    public function statusCategory($statusId)
    {
        $statusCategory = Categories::find($statusId);
        if ($statusCategory) {
            $statusVal = $statusCategory->status;
            if ($statusVal == '1') {
                $statusCategory->status = '0';
                $statusCategory->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'status updated'
                ]);
            } else {
                $statusCategory->status = '1';
                $statusCategory->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'status updated'
                ]);
            }
        }
    }

    // Tags Master
    public function tags(Request $request)
    {
        if (Auth::check()) {
            if ($request->ajax()) {
                $tags =  Tags::all();
                return datatables()->of($tags)->toJson();
            }
            return view('tags');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function addTags(Request $request)
    {

        $id = $request->tagsId;

        $validator = Validator::make($request->all(), [
            'tagName' => 'required|unique:tags,tag_name'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tags = ($id == null) ? new Tags : Tags::find($id);

        $tags->tag_name = $request->tagName;
        $tags->status = $request->tagsStatus;
        $tags->save();

        if ($id == null) {
            return response()->json([
                'status' => 200,
                'message' => 'Tag Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Tag Updated Successfully'
            ]);
        }
    }

    public function editTags($id)
    {
        $tag = Tags::find($id);

        if ($tag) {
            return response()->json([
                'status' => 200,
                'tag' => $tag
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Category Not Found"
            ]);
        }
    }

    public function deleteTag($id)
    {
        $deleteTag = Tags::find($id)->delete();
        if ($deleteTag) {
            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Failed to delete Category Data',
            ]);
        }
    }

    public function statusTag($statusId)
    {
        $statusTag = Tags::find($statusId);
        if ($statusTag) {
            $statusVal = $statusTag->status;
            if ($statusVal == '1') {
                $statusTag->status = '0';
                $statusTag->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'status updated'
                ]);
            } else {
                $statusTag->status = '1';
                $statusTag->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'status updated'
                ]);
            }
        }
    }

    // Users Master
    public function users(Request $request)
    {
        if (Auth::check()) {
            if ($request->ajax()) {
                $users =  User::all();
                return datatables()->of($users)->toJson();
            }
            return view('users');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function addUser(Request $request)
    {
        $id = $request->userId;

        if ($id == null) {
            $validator = Validator::make($request->all(), [
                'userName' => 'required',
                'userEmail' => 'required|email|unique:users,email',
                'userPhone' => 'required|min:10|max:10',
                'userPassword' => 'required|min:6'
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }

        $user = ($id == null) ? new User : User::find($id);

        $user->name = $request->userName;
        $user->email = $request->userEmail;
        $user->phone = $request->userPhone;
        $user->password = hash::make($request->userPassword);
        $user->gender = $request->userGender;
        $user->role = $request->userRole;
        $user->country = $request->userCountry;
        $user->save();

        if ($id == null) {
            return response()->json([
                'status' => 200,
                'message' => 'User Added Successfully'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'User Updated Successfully'
            ]);
        }
    }

    public function editUser($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'status' => 200,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "user Not Found"
            ]);
        }
    }

    public function deleteUser($id)
    {
        $deleteUser = User::find($id)->delete();
        if ($deleteUser) {
            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Failed to delete Category Data',
            ]);
        }
    }

    // News Master
    public function news(Request $request)
    {
        if (Auth::check()) {
            if ($request->ajax()) {
                $news =  Posts::all();
                return datatables()->of($news)->toJson();
            }
            return view('news');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function addNewsForm(Request $request)
    {
        if (Auth::check()) {

            $newsCat = Categories::get()->where('parent_category', 0);

            $newsTag = Tags::all();

            return view('addNews', compact('newsCat', 'newsTag'));
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    private function createDir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public function addNewsPost(Request $request)
    {
        $id = $request->newsId;

        if ($id == null) {
            $validator = Validator::make($request->all(), [
                'newsHeading' => 'required|unique:news,heading',
                'newsCategory' => 'required',
                'newsContent' => 'required',
                'newsTags' => 'required|array',
                'newsTags.*' => 'distinct|string',
                'newsImage' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Adjust image validation rules as neededz
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }


        $news = ($id == null) ? new Posts() : Posts::find($id);

        $news->heading = $request->newsHeading;
        $news->slug = Str::slug($news->heading, "-");
        $news->category_id = $request->newsCategory;
        $news->content = $request->newsContent;
        $news->tags = implode(',', $request->newsTags);
        $userName = Session::get('user_name');
        $news->created_by = $userName;


        // Handle image upload
        if ($request->hasFile('newsImage')) {
            $this->createDir(public_path('uploads/'));
            if ($news->image && file_exists(public_path($news->image))) {
                unlink(public_path($news->image));
            }
            $file = $request->file('newsImage');
            $fileName = $file->getClientOriginalName() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/'), $fileName);
            $news->image = 'uploads/' . $fileName;
        }

        $news->save();

        // Saving tags
        $tagsArray = explode(',', $news->tags);
        foreach ($tagsArray as $tagName) {
            $tag = Tags::firstOrNew(['tag_name' => $tagName]);
            $tag->save();
        }

        $message = ($id == null) ? 'News Added Successfully' : 'News Updated Successfully';

        return response()->json([
            'status' => 200,
            'message' => $message
        ]);
    }



    public function editNewsForm(Request $request)
    {
        $id = $request->id;

        $newsPost = Posts::find($id);

        $selectedTags = explode(',', $newsPost->tags);

        $category_id = $newsPost->category_id;

        $categoryName = Categories::find($category_id);

        $newsCat = Categories::get()->where('parent_category', 0);

        $newsTag = Tags::all();

        return view('editNews', compact('newsCat', 'newsTag', 'newsPost', 'categoryName', 'selectedTags'));
    }

    public function statusNews($statusId)
    {
        $statusNews = Posts::find($statusId);
        if ($statusNews) {
            $statusVal = $statusNews->status;
            if ($statusVal == '1') {
                $statusNews->status = '0';
                $statusNews->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'status updated'
                ]);
            } else {
                $statusNews->status = '1';
                $statusNews->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'status updated'
                ]);
            }
        }
    }

    public function deletePost($id)
    {
        $deletePost = Posts::find($id);
        $image = $deletePost->image;

        if ($image) {
            if (file_exists(public_path($image))) {
                unlink(public_path($image));
            }
        }

        $deletePost = Posts::find($id)->delete();

        if ($deletePost) {
            return response()->json([
                'status' => 200,
                'message' => 'Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Failed to delete Post Data',
            ]);
        }
    }

    public function viewPost(Request $request)
    {
        $id = $request->id;

        $posts =  Posts::find($id);

        $allPost = Posts::all();

        return view('viewPost', compact('posts','allPost'));
    }


    public function uploadImage(Request $request)
    {
        if ($request->hasfile('upload')) {

            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp", "pdf", "avif"];
            if (in_array(strtolower($extension), $allowedExtensions)) {

                $fileName = $fileName . '_' . time() . '.' . $extension;

                $this->createDir(public_path('uploads/'));

                $request->file('upload')->move(public_path('uploads/'), $fileName);

                $url = asset('uploads/' . $fileName);

                return  response()->json([
                    'fileName' => $fileName,
                    'uploaded' => 1,
                    'url' => $url
                ]);
            } else {
                return response()->json(['error' => 'File extension not allowed.'], 400);
            }
        } else {
            return response()->json(['error' => 'No file uploaded or invalid request.'], 400);
        }
    }
}
