@extends('layouts.staff')

@section('title', 'سرنخ‌ها | رشدینو')
@section('meta_description', 'فهرست مسئله‌های ثبت‌شده برای پیگیری فروش رشدینو.')

@section('content')
    <section class="section staff-page">
        <div class="shell">
            <p class="eyebrow"><span></span> مسیر فروش</p>
            <h1>مسئله‌های ثبت‌شده</h1>
            <p class="staff-lead">این فهرست برای پیگیری فروش است و فقط بعد از ورود مسئول دیده می‌شود.</p>

            <section class="work-queue" id="work-queue" aria-labelledby="work-queue-title">
                <h2 id="work-queue-title">کار امروز</h2>
                @if ($workQueue->isEmpty())
                    <p class="work-queue__empty">برای امروز کاری در صف نیست.</p>
                @else
                    @if ($workQueue->firstContacts->isNotEmpty())
                        <h3>اولین تماس</h3>
                        <ul>
                            @foreach ($workQueue->firstContacts as $lead)
                                <li>
                                    <a href="{{ $workQueue->inboxUrl($lead) }}">
                                        <strong>{{ $lead->name }}</strong>
                                        <span>{{ $lead->service->label() }} · هنوز پیگیری نشده</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($workQueue->meetings->isNotEmpty())
                        <h3>جلسهٔ پیش رو</h3>
                        <ul>
                            @foreach ($workQueue->meetings as $meeting)
                                <li>
                                    <a href="{{ $workQueue->inboxUrl($meeting->lead) }}">
                                        <strong>{{ $meeting->lead->name }}</strong>
                                        <time datetime="{{ $meeting->meets_at?->timezone('Asia/Tehran')->toIso8601String() }}">{{ $meeting->meets_at?->timezone('Asia/Tehran')->format('Y-m-d H:i') }}</time>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($workQueue->waitingProposals->isNotEmpty())
                        <h3>پیشنهاد منتظر جواب</h3>
                        <ul>
                            @foreach ($workQueue->waitingProposals as $proposal)
                                <li>
                                    <a href="{{ $workQueue->inboxUrl($proposal->lead) }}">
                                        <strong>{{ $proposal->lead->name }}</strong>
                                        <span>{{ $proposal->scope }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </section>

            <section class="lead-tally" id="lead-tally" aria-labelledby="lead-tally-title">
                <h2 id="lead-tally-title">ثبت‌ها</h2>
                @if ($tally['total'] === 0)
                    <p>هنوز ثبتی نیست.</p>
                @else
                    <p>تعداد سرنخ‌های ذخیره‌شده است و با فیلتر فهرست عوض نمی‌شود.</p>
                    <h3>اقدام</h3>
                    <ul>
                        @foreach ($tally['intents'] as $row)
                            <li>
                                <a href="{{ route('leads.index', ['intent' => $row['value']]) }}">
                                    <span>{{ $row['label'] }}</span>
                                    <strong>{{ $row['count'] }}</strong>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <h3>خدمت</h3>
                    <ul>
                        @foreach ($tally['services'] as $row)
                            <li>
                                <a href="{{ route('leads.index', ['service' => $row['value']]) }}">
                                    <span>{{ $row['label'] }}</span>
                                    <strong>{{ $row['count'] }}</strong>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section id="all-leads" aria-labelledby="all-leads-title">
                <h2 id="all-leads-title">همهٔ سرنخ‌ها</h2>

                <form class="lead-filters" method="GET" action="{{ route('leads.index') }}">
                    <div>
                        <label for="filter-status">وضعیت</label>
                        <select id="filter-status" name="status">
                            <option value="">همه</option>
                            @foreach (\App\Enums\LeadStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(($filters['status'] ?? null) === $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-service">خدمت</label>
                        <select id="filter-service" name="service">
                            <option value="">همه</option>
                            @foreach (\App\Enums\LeadServiceInterest::cases() as $service)
                                <option value="{{ $service->value }}" @selected(($filters['service'] ?? null) === $service->value)>{{ $service->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-intent">اقدام</label>
                        <select id="filter-intent" name="intent">
                            <option value="">همه</option>
                            @foreach (\App\Enums\LeadIntent::cases() as $intent)
                                <option value="{{ $intent->value }}" @selected(($filters['intent'] ?? null) === $intent->value)>{{ $intent->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lead-filters__actions">
                        <button type="submit" class="button button--ghost">نمایش</button>
                        @if (collect($filters)->filter()->isNotEmpty())
                            <a href="{{ route('leads.index') }}">پاک کردن فیلتر</a>
                        @endif
                    </div>
                </form>

            @forelse ($leads as $lead)
                <article class="lead-row" id="lead-{{ $lead->id }}">
                    <header>
                        <strong>{{ $lead->name }}</strong>
                        <span>{{ $lead->service->label() }} · {{ $lead->intent->label() }}</span>
                    </header>
                    <p>{{ $lead->contact }}</p>
                    @if (filled($lead->problem))
                        <p>{{ $lead->problem }}</p>
                    @endif
                    <small>{{ $lead->created_at?->timezone('Asia/Tehran')->format('Y-m-d H:i') }}</small>

                    <form class="lead-status" method="POST" action="{{ route('leads.status.update', $lead) }}">
                        @csrf
                        @method('PATCH')
                        <label for="status-{{ $lead->id }}">وضعیت فروش</label>
                        <select id="status-{{ $lead->id }}" name="status">
                            @foreach (\App\Enums\LeadStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected($lead->status === $status)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="button button--ghost">ثبت وضعیت</button>
                        @error('status', 'lead-'.$lead->id)
                            <p class="form-error" role="alert">{{ $message }}</p>
                        @enderror
                        @if (session('status_updated') === $lead->id)
                            <p class="lead-status__saved" role="status">وضعیت ثبت شد.</p>
                        @endif
                    </form>

                    <section class="lead-value" aria-labelledby="value-{{ $lead->id }}">
                        <h2 id="value-{{ $lead->id }}">ارزش تخمینی</h2>

                        @if ($lead->estimated_value)
                            <p><span>{{ number_format($lead->estimated_value) }}</span> تومان</p>
                        @else
                            <p class="lead-value__empty">هنوز ارزش تخمینی ثبت نشده.</p>
                        @endif

                        @php($valueErrors = $errors->getBag('value-'.$lead->id))
                        <form class="lead-value-form" method="POST" action="{{ route('leads.value.update', $lead) }}">
                            @csrf
                            @method('PATCH')
                            <label for="estimated-value-{{ $lead->id }}">مبلغ به تومان</label>
                            <input id="estimated-value-{{ $lead->id }}" name="estimated_value" type="text" inputmode="numeric" value="{{ $valueErrors->any() ? old('estimated_value') : ($lead->estimated_value ? number_format($lead->estimated_value) : '') }}" required>
                            @error('estimated_value', 'value-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="button button--ghost">ثبت ارزش</button>
                            @if (session('value_saved') === $lead->id)
                                <p class="lead-value-form__saved" role="status">ارزش ثبت شد.</p>
                            @endif
                        </form>
                    </section>

                    <section class="lead-follow-ups" aria-labelledby="follow-ups-{{ $lead->id }}">
                        <h2 id="follow-ups-{{ $lead->id }}">پیگیری‌ها</h2>

                        @forelse ($lead->followUps as $followUp)
                            <article class="lead-follow-up">
                                <header>
                                    <strong>{{ $followUp->channel->label() }}</strong>
                                    <time datetime="{{ $followUp->followed_up_at?->timezone('Asia/Tehran')->toIso8601String() }}">{{ $followUp->followed_up_at?->timezone('Asia/Tehran')->format('Y-m-d H:i') }}</time>
                                </header>
                                <p>{{ $followUp->note }}</p>
                            </article>
                        @empty
                            <p class="lead-follow-ups__empty">هنوز پیگیری‌ای ثبت نشده.</p>
                        @endforelse

                        @php($followUpErrors = $errors->getBag('follow-up-'.$lead->id))
                        <form class="lead-follow-form" method="POST" action="{{ route('leads.follow-ups.store', $lead) }}">
                            @csrf
                            <label for="followed-up-at-{{ $lead->id }}">زمان پیگیری</label>
                            <input id="followed-up-at-{{ $lead->id }}" name="followed_up_at" type="datetime-local" value="{{ $followUpErrors->any() ? old('followed_up_at') : now()->timezone('Asia/Tehran')->format('Y-m-d\TH:i') }}" required>
                            @error('followed_up_at', 'follow-up-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <label for="channel-{{ $lead->id }}">کانال</label>
                            <select id="channel-{{ $lead->id }}" name="channel" required>
                                <option value="">انتخاب کن</option>
                                @foreach (\App\Enums\FollowUpChannel::cases() as $channel)
                                    <option value="{{ $channel->value }}" @selected($followUpErrors->any() && old('channel') === $channel->value)>{{ $channel->label() }}</option>
                                @endforeach
                            </select>
                            @error('channel', 'follow-up-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <label for="note-{{ $lead->id }}">یادداشت</label>
                            <textarea id="note-{{ $lead->id }}" name="note" rows="3" maxlength="2000" required>{{ $followUpErrors->any() ? old('note') : '' }}</textarea>
                            @error('note', 'follow-up-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="button button--ghost">ثبت پیگیری</button>
                            @if (session('follow_up_saved') === $lead->id)
                                <p class="lead-follow-form__saved" role="status">پیگیری ثبت شد.</p>
                            @endif
                        </form>
                    </section>

                    <section class="lead-conversations" aria-labelledby="conversations-{{ $lead->id }}">
                        <h2 id="conversations-{{ $lead->id }}">گفتگوها</h2>

                        @forelse ($lead->conversations as $conversation)
                            <article class="lead-conversation">
                                <time datetime="{{ $conversation->conversed_at?->timezone('Asia/Tehran')->toIso8601String() }}">{{ $conversation->conversed_at?->timezone('Asia/Tehran')->format('Y-m-d H:i') }}</time>
                                <p>{{ $conversation->body }}</p>
                            </article>
                        @empty
                            <p class="lead-conversations__empty">هنوز گفتگویی ثبت نشده.</p>
                        @endforelse

                        @php($conversationErrors = $errors->getBag('conversation-'.$lead->id))
                        <form class="lead-conversation-form" method="POST" action="{{ route('leads.conversations.store', $lead) }}">
                            @csrf
                            <label for="conversed-at-{{ $lead->id }}">زمان گفتگو</label>
                            <input id="conversed-at-{{ $lead->id }}" name="conversed_at" type="datetime-local" value="{{ $conversationErrors->any() ? old('conversed_at') : now()->timezone('Asia/Tehran')->format('Y-m-d\TH:i') }}" required>
                            @error('conversed_at', 'conversation-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <label for="conversation-body-{{ $lead->id }}">متن گفتگو</label>
                            <textarea id="conversation-body-{{ $lead->id }}" name="body" rows="3" maxlength="2000" required>{{ $conversationErrors->any() ? old('body') : '' }}</textarea>
                            @error('body', 'conversation-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="button button--ghost">ثبت گفتگو</button>
                            @if (session('conversation_saved') === $lead->id)
                                <p class="lead-conversation-form__saved" role="status">گفتگو ثبت شد.</p>
                            @endif
                        </form>
                    </section>

                    <section class="lead-meetings" aria-labelledby="meetings-{{ $lead->id }}">
                        <h2 id="meetings-{{ $lead->id }}">جلسه‌ها</h2>

                        @forelse ($lead->meetings as $meeting)
                            <article class="lead-meeting">
                                <time datetime="{{ $meeting->meets_at?->timezone('Asia/Tehran')->toIso8601String() }}">{{ $meeting->meets_at?->timezone('Asia/Tehran')->format('Y-m-d H:i') }}</time>
                            </article>
                        @empty
                            <p class="lead-meetings__empty">هنوز جلسه‌ای ثبت نشده.</p>
                        @endforelse

                        @php($meetingErrors = $errors->getBag('meeting-'.$lead->id))
                        <form class="lead-meeting-form" method="POST" action="{{ route('leads.meetings.store', $lead) }}">
                            @csrf
                            <label for="meets-at-{{ $lead->id }}">زمان جلسه</label>
                            <input id="meets-at-{{ $lead->id }}" name="meets_at" type="datetime-local" value="{{ $meetingErrors->any() ? old('meets_at') : now()->timezone('Asia/Tehran')->format('Y-m-d\TH:i') }}" required>
                            @error('meets_at', 'meeting-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="button button--ghost">ثبت جلسه</button>
                            @if (session('meeting_saved') === $lead->id)
                                <p class="lead-meeting-form__saved" role="status">جلسه ثبت شد.</p>
                            @endif
                        </form>
                    </section>

                    <section class="lead-proposals" aria-labelledby="proposals-{{ $lead->id }}">
                        <h2 id="proposals-{{ $lead->id }}">پیشنهادها</h2>

                        @forelse ($lead->proposals as $proposal)
                            <article class="lead-proposal">
                                <header>
                                    <strong>{{ $proposal->status->label() }}</strong>
                                    <span>{{ number_format($proposal->amount) }} تومان</span>
                                </header>
                                <p>{{ $proposal->scope }}</p>

                                @if ($proposal->project)
                                    <p><strong>مشتری</strong> {{ $proposal->project->client->name }} — {{ $proposal->project->client->contact }}</p>
                                    <p class="lead-proposal-form__saved" role="status">پروژه شروع شد.</p>
                                @elseif ($proposal->status === \App\Enums\ProposalStatus::Accepted)
                                    <form class="lead-proposal-form" method="POST" action="{{ route('leads.proposals.convert', [$lead, $proposal]) }}">
                                        @csrf
                                        <button type="submit" class="button button--ghost">شروع پروژه</button>
                                    </form>
                                @endif

                                @error('proposal', 'convert-'.$proposal->id)
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror
                            </article>
                        @empty
                            <p class="lead-proposals__empty">هنوز پیشنهادی ثبت نشده.</p>
                        @endforelse

                        @php($proposalErrors = $errors->getBag('proposal-'.$lead->id))
                        <form class="lead-proposal-form" method="POST" action="{{ route('leads.proposals.store', $lead) }}">
                            @csrf
                            <label for="scope-{{ $lead->id }}">دامنه</label>
                            <textarea id="scope-{{ $lead->id }}" name="scope" rows="3" maxlength="2000" required>{{ $proposalErrors->any() ? old('scope') : '' }}</textarea>
                            @error('scope', 'proposal-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <label for="amount-{{ $lead->id }}">مبلغ به تومان</label>
                            <input id="amount-{{ $lead->id }}" name="amount" type="text" inputmode="numeric" value="{{ $proposalErrors->any() ? old('amount') : '' }}" required>
                            @error('amount', 'proposal-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <label for="proposal-status-{{ $lead->id }}">وضعیت پیشنهاد</label>
                            <select id="proposal-status-{{ $lead->id }}" name="status" required>
                                @foreach (\App\Enums\ProposalStatus::cases() as $proposalStatus)
                                    <option value="{{ $proposalStatus->value }}" @selected(($proposalErrors->any() ? old('status') : \App\Enums\ProposalStatus::Draft->value) === $proposalStatus->value)>{{ $proposalStatus->label() }}</option>
                                @endforeach
                            </select>
                            @error('status', 'proposal-'.$lead->id)
                                <p class="form-error" role="alert">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="button button--ghost">ثبت پیشنهاد</button>
                            @if (session('proposal_saved') === $lead->id)
                                <p class="lead-proposal-form__saved" role="status">پیشنهاد ثبت شد.</p>
                            @endif
                        </form>
                    </section>

                    @foreach ($lead->projects as $project)
                        <section class="lead-project-tasks" aria-labelledby="project-tasks-{{ $lead->id }}-{{ $project->id }}">
                            <h2 id="project-tasks-{{ $lead->id }}-{{ $project->id }}">وظایف پروژه</h2>
                            <p class="lead-project-tasks__scope">{{ $project->scope }}</p>

                            @forelse ($project->tasks as $task)
                                <article class="lead-project-task">
                                    <header>
                                        <strong>{{ $task->title }}</strong>
                                        <span>{{ $task->status->label() }}</span>
                                    </header>
                                    @if ($task->status === \App\Enums\ProjectTaskStatus::Open)
                                        @php($taskStatusErrors = $errors->getBag('project-task-status-'.$lead->id.'-'.$project->id.'-'.$task->id))
                                        <form class="lead-project-task-form" method="POST" action="{{ route('leads.projects.tasks.status.update', [$lead, $project, $task]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ \App\Enums\ProjectTaskStatus::Done->value }}">
                                            <button type="submit" class="button button--ghost">انجام شد</button>
                                        </form>
                                        @error('status', 'project-task-status-'.$lead->id.'-'.$project->id.'-'.$task->id)
                                            <p class="form-error" role="alert">{{ $message }}</p>
                                        @enderror
                                    @endif
                                </article>
                            @empty
                                <p class="lead-project-tasks__empty">هنوز وظیفه‌ای ثبت نشده.</p>
                            @endforelse

                            @php($taskErrors = $errors->getBag('project-task-'.$lead->id.'-'.$project->id))
                            <form class="lead-project-task-form" method="POST" action="{{ route('leads.projects.tasks.store', [$lead, $project]) }}">
                                @csrf
                                <label for="task-title-{{ $lead->id }}-{{ $project->id }}">عنوان وظیفه</label>
                                <input id="task-title-{{ $lead->id }}-{{ $project->id }}" name="title" type="text" maxlength="500" value="{{ $taskErrors->any() ? old('title') : '' }}" required>
                                @error('title', 'project-task-'.$lead->id.'-'.$project->id)
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="button button--ghost">ثبت وظیفه</button>
                            </form>
                            @if (session('project_task_saved') === $lead->id)
                                <p class="lead-project-task-form__saved" role="status">وظیفه ثبت شد.</p>
                            @endif
                            @if (session('project_task_status_saved') === $lead->id)
                                <p class="lead-project-task-form__saved" role="status">وضعیت وظیفه ثبت شد.</p>
                            @endif
                        </section>

                        <section class="lead-project-deliveries" aria-labelledby="project-deliveries-{{ $lead->id }}-{{ $project->id }}">
                            <h2 id="project-deliveries-{{ $lead->id }}-{{ $project->id }}">تحویل‌ها</h2>

                            @forelse ($project->deliveries as $delivery)
                                <article class="lead-project-delivery">
                                    <header>
                                        <strong>تحویل</strong>
                                        <time datetime="{{ $delivery->delivered_at?->timezone('Asia/Tehran')->toIso8601String() }}">{{ $delivery->delivered_at?->timezone('Asia/Tehran')->format('Y-m-d H:i') }}</time>
                                    </header>
                                    <p>{{ $delivery->note }}</p>
                                </article>
                            @empty
                                <p class="lead-project-deliveries__empty">هنوز تحویلی ثبت نشده.</p>
                            @endforelse

                            @php($deliveryErrors = $errors->getBag('project-delivery-'.$lead->id.'-'.$project->id))
                            <form class="lead-project-delivery-form" method="POST" action="{{ route('leads.projects.deliveries.store', [$lead, $project]) }}">
                                @csrf
                                <label for="delivered-at-{{ $lead->id }}-{{ $project->id }}">زمان تحویل</label>
                                <input id="delivered-at-{{ $lead->id }}-{{ $project->id }}" name="delivered_at" type="datetime-local" value="{{ $deliveryErrors->any() ? old('delivered_at') : now()->timezone('Asia/Tehran')->format('Y-m-d\TH:i') }}" required>
                                @error('delivered_at', 'project-delivery-'.$lead->id.'-'.$project->id)
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror

                                <label for="delivery-note-{{ $lead->id }}-{{ $project->id }}">یادداشت تحویل</label>
                                <textarea id="delivery-note-{{ $lead->id }}-{{ $project->id }}" name="note" rows="3" maxlength="2000" required>{{ $deliveryErrors->any() ? old('note') : '' }}</textarea>
                                @error('note', 'project-delivery-'.$lead->id.'-'.$project->id)
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror

                                <button type="submit" class="button button--ghost">ثبت تحویل</button>
                                @if (session('project_delivery_saved') === $lead->id)
                                    <p class="lead-project-delivery-form__saved" role="status">تحویل ثبت شد.</p>
                                @endif
                            </form>
                        </section>

                        <section class="lead-project-supports" aria-labelledby="project-supports-{{ $lead->id }}-{{ $project->id }}">
                            <h2 id="project-supports-{{ $lead->id }}-{{ $project->id }}">پشتیبانی</h2>

                            @forelse ($project->supports as $support)
                                <article class="lead-project-support">
                                    <header>
                                        <strong>ثبت</strong>
                                        <time datetime="{{ $support->supported_at?->timezone('Asia/Tehran')->toIso8601String() }}">{{ $support->supported_at?->timezone('Asia/Tehran')->format('Y-m-d H:i') }}</time>
                                    </header>
                                    <p>{{ $support->note }}</p>
                                </article>
                            @empty
                                <p class="lead-project-supports__empty">هنوز یادداشت پشتیبانی ثبت نشده.</p>
                            @endforelse

                            @php($supportErrors = $errors->getBag('project-support-'.$lead->id.'-'.$project->id))
                            <form class="lead-project-support-form" method="POST" action="{{ route('leads.projects.supports.store', [$lead, $project]) }}">
                                @csrf
                                <label for="supported-at-{{ $lead->id }}-{{ $project->id }}">زمان</label>
                                <input id="supported-at-{{ $lead->id }}-{{ $project->id }}" name="supported_at" type="datetime-local" value="{{ $supportErrors->any() ? old('supported_at') : now()->timezone('Asia/Tehran')->format('Y-m-d\TH:i') }}" required>
                                @error('supported_at', 'project-support-'.$lead->id.'-'.$project->id)
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror

                                <label for="support-note-{{ $lead->id }}-{{ $project->id }}">یادداشت</label>
                                <textarea id="support-note-{{ $lead->id }}-{{ $project->id }}" name="note" rows="3" maxlength="2000" required>{{ $supportErrors->any() ? old('note') : '' }}</textarea>
                                @error('note', 'project-support-'.$lead->id.'-'.$project->id)
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror

                                <button type="submit" class="button button--ghost">ثبت پشتیبانی</button>
                                @if (session('project_support_saved') === $lead->id)
                                    <p class="lead-project-support-form__saved" role="status">پشتیبانی ثبت شد.</p>
                                @endif
                            </form>
                        </section>
                    @endforeach
                </article>
            @empty
                <p class="staff-lead">{{ collect($filters)->filter()->isNotEmpty() ? 'با این فیلتر سرنخی نیست.' : 'هنوز مسئله‌ای ثبت نشده.' }}</p>
            @endforelse

            @if ($leads->hasPages())
                <nav class="staff-pages" aria-label="صفحه‌های سرنخ">
                    @if ($leads->onFirstPage())
                        <span>قبلی</span>
                    @else
                        <a href="{{ $leads->previousPageUrl() }}">قبلی</a>
                    @endif
                    <span>{{ $leads->currentPage() }} از {{ $leads->lastPage() }}</span>
                    @if ($leads->hasMorePages())
                        <a href="{{ $leads->nextPageUrl() }}">بعدی</a>
                    @else
                        <span>بعدی</span>
                    @endif
                </nav>
            @endif
            </section>
        </div>
    </section>
@endsection
