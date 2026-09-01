@props(['testimonial'])

<article class="flex h-full flex-col rounded-2xl bg-surface p-8">
    <div class="mb-6 flex gap-1 text-amber-400" aria-label="{{ $testimonial->rating }} out of 5 stars">
        @for ($i = 0; $i < $testimonial->rating; $i++)
            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        @endfor
    </div>

    <blockquote class="flex-1 text-base leading-relaxed text-white/90 italic">
        "{{ $testimonial->quote }}"
    </blockquote>

    <div class="mt-8 border-t border-white/5 pt-6">
        <p class="font-semibold text-white">{{ $testimonial->author }}</p>
        <p class="mt-1 text-sm text-muted">{{ $testimonial->metaLine() }}</p>
    </div>
</article>
