@extends('backend.layout.app')

@section('content')
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-7xl space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h5 class="text-2xl font-bold tracking-tight text-gray-900">Blog</h5>
        <p class="text-sm text-gray-500">AI writes a draft. You check it and publish it.</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('blog.index') }}" target="_blank"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
          <i class="fas fa-up-right-from-square"></i> View blog
        </a>
        <a href="{{ route('admin.blog.create') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
          <i class="fas fa-pen"></i> Write manually
        </a>
      </div>
    </div>

    @if (session('success'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Generate --}}
    <div class="grid gap-6 lg:grid-cols-5">
      <form method="POST" action="{{ route('admin.blog.generate') }}" id="generateForm"
            class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-3">
        @csrf
        <h6 class="flex items-center gap-2 text-lg font-semibold text-gray-900">
          <i class="fas fa-wand-magic-sparkles text-indigo-600"></i> Generate a post with AI
        </h6>
        <p class="mt-1 text-sm text-gray-600">
          Leave the topic empty to write about today's top tech news, or type your own topic.
          The post uses your product list and prices, and is saved as a <strong>draft</strong>.
        </p>

        <label for="topic" class="mt-5 block text-sm font-medium text-gray-700">Topic (optional)</label>
        <input id="topic" name="topic" type="text" maxlength="200" value="{{ old('topic') }}"
               placeholder="e.g. Best laptops for students under Rs 80,000 in Nepal"
               class="mt-1 w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        <p class="mt-2 text-xs text-gray-500">
          Topics that work well: buying guides, "X vs Y" comparisons, price lists, "which printer for an office", new arrivals.
        </p>

        <button type="submit" id="generateBtn"
                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-wait disabled:opacity-70">
          <i class="fas fa-wand-magic-sparkles"></i> <span>Generate draft</span>
        </button>
        <p id="generateWait" class="mt-3 hidden text-sm text-gray-600">
          <i class="fas fa-spinner fa-spin"></i> Writing the post. This takes about 30–90 seconds, so keep this page open.
        </p>
      </form>

      <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">
        <h6 class="text-sm font-semibold text-gray-900">Today's tech headlines the AI picks from</h6>
        <ul class="mt-3 max-h-72 space-y-2 overflow-y-auto pr-1 text-sm">
          @forelse ($headlines as $h)
            <li class="leading-snug">
              <span class="text-xs font-medium text-gray-500">{{ $h['site'] }}</span><br>
              <a href="{{ $h['url'] }}" target="_blank" rel="noopener" class="text-gray-800 hover:text-indigo-600">{{ $h['title'] }}</a>
            </li>
          @empty
            <li class="text-gray-500">Couldn't load news feeds right now. You can still type your own topic.</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- Posts --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-left text-gray-600">
            <tr>
              <th class="px-4 py-3 font-medium">Title</th>
              <th class="px-4 py-3 font-medium">Status</th>
              <th class="px-4 py-3 font-medium">Created</th>
              <th class="px-4 py-3 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse ($posts as $p)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                  <a href="{{ route('admin.blog.edit', $p) }}" class="font-medium text-gray-900 hover:text-indigo-600">{{ $p->title }}</a>
                  @if ($p->ai_generated)<span class="ml-2 rounded bg-indigo-50 px-1.5 py-0.5 text-xs text-indigo-700">AI</span>@endif
                </td>
                <td class="px-4 py-3">
                  @if ($p->isPublished())
                    <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Published</span>
                  @else
                    <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">Draft</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $p->created_at->format('d M Y') }}</td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <a href="{{ route('admin.blog.edit', $p) }}" class="text-indigo-600 hover:underline">Edit</a>
                    <a href="{{ $p->url() }}" target="_blank" class="text-gray-600 hover:underline">{{ $p->isPublished() ? 'View' : 'Preview' }}</a>
                    <form action="{{ route('admin.blog.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                      @csrf @method('DELETE')
                      <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="px-4 py-10 text-center text-gray-500">No posts yet. Generate your first one above.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    {{ $posts->links() }}
  </div>
</section>

<script>
  document.getElementById('generateForm').addEventListener('submit', function () {
    const btn = document.getElementById('generateBtn');
    btn.disabled = true;
    btn.querySelector('span').textContent = 'Generating…';
    document.getElementById('generateWait').classList.remove('hidden');
  });
</script>
@endsection
