<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Payroll Lines</h2>
            <a href="{{ route('admin.payroll.index') }}" class="px-3 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="bg-white p-4 rounded shadow-sm grid grid-cols-1 md:grid-cols-8 gap-3">
                <input type="date" name="from" value="{{ $from }}" class="border rounded px-3 py-2">
                <input type="date" name="to" value="{{ $to   }}" class="border rounded px-3 py-2">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All statuses</option>
                    @foreach(['pending','approved','paid'] as $s)
                    <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <input type="number" name="staff_id" value="{{ $staffId }}" placeholder="Staff ID"
                    class="border rounded px-3 py-2">
                <div class="md:col-span-4 flex justify-end">
                    <a href="{{ route('admin.payroll.lines') }}" class="px-3 py-2 border rounded mr-2">Reset</a>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Apply</button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.payroll.mark') }}" class="bg-white p-4 rounded shadow-sm">
                @csrf @method('PATCH')
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="py-2"><input type="checkbox"
                                        onclick="document.querySelectorAll('.chk').forEach(c=>c.checked=this.checked)">
                                </th>
                                <th class="py-2 text-left">Date</th>
                                <th class="py-2 text-left">Event</th>
                                <th class="py-2 text-left">Staff</th>
                                <th class="py-2 text-left">Rate</th>
                                <th class="py-2 text-left">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lines as $l)
                            <tr class="border-t">
                                <td class="py-2"><input type="checkbox" name="ids[]" value="{{ $l->id }}" class="chk">
                                </td>
                                <td class="py-2">{{ \Illuminate\Support\Carbon::parse($l->event_date)->format('Y-m-d')
                                    }}</td>
                                <td class="py-2">
                                    <a href="{{ route('admin.events.show', $l->event_id) }}"
                                        class="text-indigo-600 underline">
                                        {{ $l->event_name }}
                                    </a>
                                </td>
                                <td class="py-2">{{ $l->name }}</td>
                                <td class="py-2">₱{{ number_format($l->pay_rate,2) }}</td>
                                <td class="py-2 capitalize">{{ $l->pay_status }}</td>
                                <td></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">No lines.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <select name="status" class="border rounded px-3 py-2">
                        @foreach(['pending','approved','paid'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Mark Selected</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>