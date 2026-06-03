<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKbArticleRequest;
use App\Models\Category;
use App\Models\KbArticle;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KbArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = KbArticle::with(['category', 'author']);

        if (Auth::user()->isUser()) {
            $query->published();
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('body', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $articles   = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('knowledge-base.index', compact('articles', 'categories'));
    }

    public function show(KbArticle $kbArticle)
    {
        if (Auth::user()->isUser() && !$kbArticle->is_published) {
            abort(404);
        }

        return view('knowledge-base.show', ['article' => $kbArticle]);
    }

    public function create()
    {
        $this->authorizeStaff();
        $categories = Category::active()->get();

        return view('knowledge-base.form', [
            'article'    => new KbArticle(),
            'categories' => $categories,
        ]);
    }

    public function store(StoreKbArticleRequest $request)
    {
        $article = KbArticle::create(array_merge(
            $request->validated(),
            ['author_id' => Auth::id()]
        ));

        AuditLogger::log('kb.created', $article, null, ['title' => $article->title]);

        return redirect()->route('kb.show', $article)->with('success', 'Article saved.');
    }

    public function edit(KbArticle $kbArticle)
    {
        $this->authorizeStaff();
        $categories = Category::active()->get();

        return view('knowledge-base.form', [
            'article'    => $kbArticle,
            'categories' => $categories,
        ]);
    }

    public function update(StoreKbArticleRequest $request, KbArticle $kbArticle)
    {
        $old = $kbArticle->only('title', 'is_published');
        $kbArticle->update($request->validated());

        AuditLogger::log('kb.updated', $kbArticle, $old, $kbArticle->only('title', 'is_published'));

        return redirect()->route('kb.show', $kbArticle)->with('success', 'Article updated.');
    }

    private function authorizeStaff(): void
    {
        if (!Auth::user()->isStaffOrAdmin()) {
            abort(403);
        }
    }
}
