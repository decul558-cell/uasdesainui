<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.form');
    }

    private function uploadThumbnail(array $files, ?string $oldThumbnail = null): ?string
    {
        if (!isset($files['thumbnail']) || $files['thumbnail']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmpPath  = $files['thumbnail']['tmp_name'];
        $origName = $files['thumbnail']['name'];
        $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            return null;
        }

        $filename = 'thumbnails/' . Str::random(40) . '.' . $ext;
        $destPath = storage_path('app/public/' . $filename);

        if (!is_dir(dirname($destPath))) {
            mkdir(dirname($destPath), 0755, true);
        }

        if (move_uploaded_file($tmpPath, $destPath)) {
            if ($oldThumbnail) {
                Storage::disk('public')->delete($oldThumbnail);
            }
            return $filename;
        }

        return null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body'  => 'required',
        ]);

        $data                 = $request->only(['title', 'excerpt', 'body', 'status']);
        $data['slug']         = Str::slug($request->title) . '-' . Str::random(5);
        $data['user_id']      = Auth::id();
        $data['published_at'] = $request->status === 'published' ? now() : null;

        $thumb = $this->uploadThumbnail($_FILES);
        if ($thumb) {
            $data['thumbnail'] = $thumb;
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required',
            'body'  => 'required',
        ]);

        $data = $request->only(['title', 'excerpt', 'body', 'status']);

        if ($request->status === 'published' && !$article->published_at) {
            $data['published_at'] = now();
        }

        $thumb = $this->uploadThumbnail($_FILES, $article->thumbnail);
        if ($thumb) {
            $data['thumbnail'] = $thumb;
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diupdate!');
    }

    public function destroy(Article $article)
    {
        if ($article->thumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }
        $article->delete();
        return back()->with('success', 'Artikel berhasil dihapus!');
    }
}
