<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payroll Summary</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <form method="GET" class="bg-white p-4 rounded shadow-sm grid grid-cols-1 md:grid-cols-6 gap-3">
                <input type="date" name="from" value="{{ $from }}" class="border rounded px-3 py-2">
                <input type="date" name="to" value="{{ $to   }}" class="border rounded px-3 py-2">
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All statuses</option>
                    @foreach(['pending','approved','paid'] as $s)
                    <option value="{{ $s }}" @selected($status===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <div class="md:col-span-3 flex justify-end">
                    <a href="{{ route('admin.payroll.index') }}" class="px-3 py-2 border rounded mr-2">Reset</a>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Apply</button>
                </div>
            </form>

            <div class="bg-white p-4 rounded shadow-sm overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="py-2 text-left">Staff</th>
                            <th class="py-2 text-left">Events</th>
                            <th class="py-2 text-left">Total Pay</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                        <tr class="border-t">
                            <td class="py-2">{{ $r->name }}</td>
                            <td class="py-2">{{ $r->events_count }}</td>
                            <td class="py-2">₱{{ number_format($r->total_pay,2) }}</td>
                            <td class="py-2">
                                <a href="{{ route('admin.payroll.lines', ['from'=>$from,'to'=>$to,'status'=>$status,'staff_id'=>$r->staff_id]) }}"
                                    class="text-indigo-600 underline">View lines</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>