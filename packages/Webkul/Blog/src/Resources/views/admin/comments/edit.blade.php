<x-admin::layouts>
    <x-slot:title>
        @lang('blog::app.comment.edit.title')
    </x-slot:title>

    @php
        $currentLocale = core()->getRequestedLocale();
    @endphp
    
    <!-- Blog Edit Form -->
    <x-admin::form
        :action="route('admin.blog.comment.update', $comment->id)"
        method="POST"
        enctype="multipart/form-data"
    >
        {!! view_render_event('admin.blog.comments.edit.before') !!}

        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-5 font-bold text-gray-800 dark:text-white">
                @lang('blog::app.comments.edit.title')
            </p>

            <div class="flex items-center gap-x-3">
                <!-- Back Button -->
                <a
                    href="{{ route('admin.blog.tag.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                >
                    @lang('blog::app.comments.edit.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('blog::app.comments.edit.btn-title')
                </button>
            </div>
        </div>

        <!-- Full Panel -->
        <div class="mt-4 flex gap-3 max-xl:flex-wrap">

            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">

                <!-- General -->
                <div class="box-shadow rounded-1 bg-white p-4 dark:bg-gray-900">
                    <p class="text-4 mb-4 font-semibold text-gray-800 dark:text-white">
                        @lang('blog::app.comments.edit.general')
                    </p>

                    <!-- Locales -->
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="locale"
                        value="en"
                    >
                    </x-admin::form.control-group.control>

                    <!-- Author ID -->
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="author"
                        :value="$comment->author"
                    >
                    </x-admin::form.control-group.control>

                    <!-- Name -->
                    <x-admin::form.control-group class="mb-2.5">
                        <x-admin::form.control-group.label class="required">
                            @lang('blog::app.comments.edit.post')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="text"
                            name="post"
                            value="{{ old('post') ?? $comment->blog->name }}"
                            label="{{ trans('blog::app.comments.edit.post') }}"
                            rules="required"
                            v-slot="{ field }"
                            disabled="disabled"
                        >
                            <input
                                type="text"
                                name="post"
                                id="post"
                                v-bind="field"
                                :class="[errors['{{ 'post' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                class="flex min-h-10 w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                placeholder="{{ trans('blog::app.comments.edit.post') }}"
                                v-slugify-target:slug="setValues"
                                disabled="disabled"
                            >
                        </v-field>

                        <x-admin::form.control-group.error
                            control-name="post"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Slug -->
                    <x-admin::form.control-group class="mb-2.5">
                        <x-admin::form.control-group.label class="required">
                            @lang('blog::app.comments.edit.name')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="text"
                            name="author_name"
                            {{-- value="{{ old('author_name') ?? $author_name }}" --}}
                            value="{{ old('author_name') ?? $comment->name }}"
                            label="{{ trans('blog::app.comments.edit.name') }}"
                            rules="required"
                            v-slot="{ field }"
                            disabled="disabled"
                        >
                            <input
                                type="text"
                                name="author_name"
                                id="author_name"
                                v-bind="field"
                                :class="[errors['{{ 'author_name' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                class="flex min-h-10 w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                placeholder="{{ trans('blog::app.comments.edit.name') }}"
                                v-slugify-target:slug
                                disabled="disabled"
                            >
                        </v-field>

                        <x-admin::form.control-group.error
                            control-name="author_name"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Published At -->
                    <x-admin::form.control-group class="mb-2.5 w-full">
                        <x-admin::form.control-group.label class="required">
                            @lang('blog::app.comments.edit.comment-date')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="date"
                            name="created_at" 
                            id="created_at"
                            rules="required"
                            disabled="disabled"
                            :value="old('created_at') ?? date_format(date_create($comment->created_at),'Y-m-d')"
                            :label="trans('blog::app.comments.edit.comment-date')"
                            :placeholder="trans('blog::app.comments.edit.comment-date')"
                        >
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error
                            control-name="created_at"
                        >
                        </x-admin::form.control-group.error>
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <v-description>
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="required">
                                @lang('blog::app.comments.edit.comment')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="comment"
                                id="comment"
                                class="comment"
                                rules="required"
                                :value="old('comment') ?? $comment->comment"
                                :label="trans('blog::app.comments.edit.comment')"
                                :tinymce="true"
                                :prompt="core()->getConfigData('general.magic_ai.content_generation.category_description_prompt')"
                            >
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="comment"
                            >
                            </x-admin::form.control-group.error>
                        </x-admin::form.control-group>
                    </v-description>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <!-- Settings -->

                <x-admin::accordion>
                    <x-slot:header>
                        <p class="text-4 p-3 font-semibold text-gray-600 dark:text-gray-300">
                            @lang('blog::app.comments.edit.settings')
                        </p>
                    </x-slot:header>

                    <x-slot:content>
                        <!-- Status -->
                        <x-admin::form.control-group class="mb-2.5">
                            <x-admin::form.control-group.label class="font-medium text-gray-800 dark:text-white">
                                @lang('blog::app.comments.edit.status.title')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                id="status"
                                :value="old('status') ?? $comment->status"
                                :label="trans('blog::app.comments.edit.status.title')"
                            >
                                <!-- Options -->
                                @foreach($statusDetails as $status)
                                    <option value="{{ $status['id'] }}" {{ $comment->status == $status['id'] ? 'selected' : '' }} >@lang($status_data['name'])</option>
                                @endforeach
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </x-slot:content>
                </x-admin::accordion>
                {!! view_render_event('admin.blog.comments.edit.after', ['comment' => $comment]) !!}
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>