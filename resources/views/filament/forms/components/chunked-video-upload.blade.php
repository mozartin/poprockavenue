@php
    $statePath = $getStatePath();
    $uploadUrl = $getUploadUrl();
    $directory = $getDirectory();
    $chunkSize = $getChunkSizeBytes();
    $maxSize = $getMaxSizeBytes();
    $current = $getState();
    $currentUrl = null;

    if (is_string($current) && $current !== '') {
        $relative = ltrim($current, '/');
        $currentUrl = str_starts_with($relative, 'http')
            ? $relative
            : \Illuminate\Support\Facades\Storage::disk('public')->url($relative);
    }
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        wire:ignore
        x-data="{
            uploadUrl: @js($uploadUrl),
            directory: @js($directory),
            chunkSize: {{ $chunkSize }},
            maxSize: {{ $maxSize }},
            statePath: @js($statePath),
            progress: 0,
            uploading: false,
            error: null,
            currentPath: @js(is_string($current) ? $current : null),
            currentUrl: @js($currentUrl),
            csrf() {
                return document.querySelector('meta[name=csrf-token]')?.content
                    || document.querySelector('input[name=_token]')?.value
                    || ''
            },
            uuid() {
                if (crypto.randomUUID) return crypto.randomUUID()
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
                    const r = Math.random() * 16 | 0
                    const v = c === 'x' ? r : (r & 0x3 | 0x8)
                    return v.toString(16)
                })
            },
            async onFileChange(event) {
                const file = event.target.files?.[0]
                event.target.value = ''
                if (! file) return

                this.error = null

                if (file.size > this.maxSize) {
                    this.error = 'File is larger than 1GB.'
                    return
                }

                const ext = (file.name.split('.').pop() || '').toLowerCase()
                if (! ['mp4', 'mov', 'webm', 'm4v'].includes(ext)) {
                    this.error = 'Use MP4, MOV, WebM, or M4V.'
                    return
                }

                this.uploading = true
                this.progress = 0

                const uploadId = this.uuid()
                const totalChunks = Math.max(1, Math.ceil(file.size / this.chunkSize))

                try {
                    for (let index = 0; index < totalChunks; index++) {
                        const start = index * this.chunkSize
                        const blob = file.slice(start, start + this.chunkSize)
                        const body = new FormData()
                        body.append('upload_id', uploadId)
                        body.append('chunk_index', String(index))
                        body.append('total_chunks', String(totalChunks))
                        body.append('file_name', file.name)
                        body.append('directory', this.directory)
                        body.append('chunk', blob, file.name)

                        const response = await fetch(this.uploadUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf(),
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                            body,
                        })

                        const payload = await response.json().catch(() => ({}))

                        if (! response.ok) {
                            const message = payload.message
                                || Object.values(payload.errors || {})?.[0]?.[0]
                                || `Upload failed (HTTP ${response.status})`
                            throw new Error(message)
                        }

                        this.progress = Math.round(((index + 1) / totalChunks) * 100)

                        if (payload.done && payload.path) {
                            this.currentPath = payload.path
                            this.currentUrl = null
                            this.$wire.set(this.statePath, payload.path)
                        }
                    }
                } catch (e) {
                    this.error = e?.message || 'Upload failed.'
                } finally {
                    this.uploading = false
                }
            },
            clear() {
                this.currentPath = null
                this.currentUrl = null
                this.progress = 0
                this.error = null
                this.$wire.set(this.statePath, null)
            },
        }"
        class="space-y-3"
    >
        <template x-if="currentPath">
            <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <div class="font-medium">Current video</div>
                        <div class="break-all text-xs opacity-80" x-text="currentPath"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <template x-if="currentUrl">
                            <a
                                :href="currentUrl"
                                target="_blank"
                                rel="noopener"
                                class="text-primary-600 hover:underline dark:text-primary-400"
                            >Open</a>
                        </template>
                        <button
                            type="button"
                            class="text-danger-600 hover:underline"
                            @click="clear()"
                            x-show="! uploading"
                        >Remove</button>
                    </div>
                </div>
            </div>
        </template>

        <label
            class="flex cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white px-4 py-8 text-center transition hover:border-primary-400 dark:border-gray-600 dark:bg-gray-900"
            :class="{ 'pointer-events-none opacity-60': uploading }"
        >
            <span class="text-sm font-medium text-gray-800 dark:text-gray-100" x-text="uploading ? ('Uploading… ' + progress + '%') : 'Choose video'"></span>
            <span class="mt-1 text-xs text-gray-500 dark:text-gray-400">MP4 / MOV / WebM up to 1GB · any orientation · uploaded in small chunks</span>
            <input
                type="file"
                class="hidden"
                accept="video/mp4,video/quicktime,video/webm,video/x-m4v,.mp4,.mov,.webm,.m4v"
                @change="onFileChange"
                :disabled="uploading"
            >
        </label>

        <div x-show="uploading" class="h-2 overflow-hidden rounded bg-gray-200 dark:bg-gray-700">
            <div class="h-full bg-primary-500 transition-all" :style="`width: ${progress}%`"></div>
        </div>

        <p x-show="error" x-text="error" class="text-sm text-danger-600"></p>
    </div>
</x-dynamic-component>
