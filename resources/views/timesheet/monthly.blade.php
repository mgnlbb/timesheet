<x-app-layout>
    <x-slot name="header">
        
        <h2 class="text-xl font-bold leading-tight text-white-800">
            Timesheet Bulan {{ $month_name }}
        </h2>

    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between mb-2">
            <a href="{{ route('timesheet.monthly', ['year' => \Carbon\Carbon::create($year, $month)->subMonth()->year, 'month' => \Carbon\Carbon::create($year, $month)->subMonth()->month]) }}"
               class="text-blue-600 hover:underline">← Bulan Sebelumnya</a>
            <a href="{{ route('timesheet.monthly', ['year' => \Carbon\Carbon::create($year, $month)->addMonth()->year, 'month' => \Carbon\Carbon::create($year, $month)->addMonth()->month]) }}"
               class="text-blue-600 hover:underline">Bulan Berikutnya →</a>
        </div>
    </div>
    
    <div class="py-6 shadow-xl" x-data="timesheetModal()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            
            <div class="bg-gray-100 overflow-auto shadow-sm sm:rounded-lg">
                
                <div class="p-6">

                    @if (session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4 p-4 bg-gray-50 rounded-md border border-gray-200">
                        <h2 class="text-lg font-semibold mb-2">Import Data Timesheet</h2>
                        <p class="text-sm text-gray-600 mb-3">
                            Kamu bisa unggah file Excel berisi data timesheet dari starconnect. Pastikan format file sesuai template yang telah ditentukan. 
                            Hanya mendukung file berformat <strong>.xlsx</strong> atau <strong>.xls</strong>.
                            @if (Storage::exists('templates/timesheet_template.xlsx'))
                                <a href="{{ Storage::url('templates/timesheet_template.xlsx') }}" class="text-blue-600 underline ml-1">Unduh Template</a>
                            @endif
                        </p>
                    
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            {{-- Form Import --}}
                            <form action="{{ route('timesheet.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                                @csrf
                                <input type="file" name="excel_file" accept=".xlsx,.xls"
                                    class="border border-gray-300 rounded px-3 py-2 w-full md:w-auto" required>
                                <button type="submit"
                                    class="inline-flex items-center gap-2 bg-gray-500 dark:bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-800 transition-all">Import Excel</button>
                            </form>
                    
                        </div>
                    </div>

                    <div class="mb-4 p-4 bg-gray-50 rounded-md border border-gray-200">
                        {{-- Export Buttons --}}
                        <div class="flex items-center mb-3">
                            <a href="{{ route('timesheet.export.pdf', ['year' => $year, 'month' => $month]) }}"
                                class="inline-flex items-center gap-2 bg-gray-500 dark:bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-800 transition-all mr-3">
                                📄 Export PDF
                            </a>
                            <a href="{{ route('timesheet.export.excel', ['year' => $currentYear, 'month' => $currentMonth]) }}"
                                class="inline-flex items-center gap-2 bg-gray-500 dark:bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-700 dark:hover:bg-gray-800 transition-all">
                                📊 Export Excel
                            </a>
                        </div>
    
                        <div class="w-full shadow-md border sm:rounded-lg">
                            <table class="w-full table-auto text-sm border border-gray-300 dark:border-gray-700 sm:rounded-lg">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 mt-2">
                                    <tr>
                                        <th class="px-3 py-2 text-left border-b">Tanggal</th>
                                        <th class="px-3 py-2 text-left border-b">Hari</th>
                                        <th class="px-3 py-2 text-left border-b">Start</th>
                                        <th class="px-3 py-2 text-left border-b">End</th>
                                        <th class="px-3 py-2 text-left border-b">Total Hours</th>
                                        <th class="px-3 py-2 text-left border-b">Activity</th>
                                        <th class="px-3 py-2 w-80 text-left border-b">Remarks</th>
                                        <th class="px-3 py-2 text-left border-b">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-gray-900">
                                    @foreach ($dates as $data)
                                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-3 py-2">{{ $data['date'] }}</td>
                                            <td class="px-3 py-2">{{ $data['day_name'] }}</td>
                                            <td class="px-3 py-2">{{ $data['entry']->start_time ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $data['entry']->end_time ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $data['entry']->total_hours ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $data['entry']->activity ?? '-' }}</td>
                                            <td class="px-3 py-2 w-80">{{ $data['entry']->remarks ?? '-' }}</td>
                                            <td class="px-3 py-2">
                                                <button class="text-blue-600 hover:text-blue-800"
                                                    @click="triggerModal($event)"
                                                    data-date="{{ $data['date'] }}"
                                                    data-day="{{ $data['day_name'] }}"
                                                    data-start="{{ $data['entry']->start_time ?? '' }}"
                                                    data-end="{{ $data['entry']->end_time ?? '' }}"
                                                    data-activity="{{ $data['entry']->activity ?? '' }}"
                                                    data-remarks="{{ $data['entry']->remarks ?? '' }}"
                                                    title="{{ $data['entry'] ? 'Edit' : 'Tambah' }}">
                                                    
                                                    @if ($data['entry'])
                                                        <!-- Edit Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6H9v-2z" />
                                                        </svg>
                                                    @else
                                                        <!-- Plus Icon -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    

                    <div class="mt-6 bg-white p-4 shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-black">Rekap Kehadiran</h3>
                        <ul class="list-disc list-inside text-sm text-gray-700">
                            <li>Total Workdays: {{ $totalWorkdays }}</li>
                            <li>Total Holidays: {{ $totalHolidays }}</li>
                            <li>Total Leaves: {{ $totalLeaves }}</li>
                            <li>Total Sicks: {{ $totalSicks }}</li>
                            <li>Total Absences (belum diisi): {{ $totalAbsences }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="isOpen" x-transition x-cloak 
        @click.self="closeModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-xl max-h-screen overflow-y-auto">
                <h3 class="text-lg font-bold mb-4">Form Timesheet - <span x-text="modalDate"></span> (<span x-text="modalDay"></span>)</h3>
                <form method="POST" action="{{ route('timesheet.store') }}">
                    @csrf
                    <input type="hidden" name="date" x-model="modalDate">

                    <div class="mb-4">
                        <label>Activity</label>
                        <div class="flex gap-4 mt-1">
                            <label><input type="radio" name="activity" value="Workday" x-model="activity" @change="handleActivity"> Workday</label>
                            <label><input type="radio" name="activity" value="Holiday" x-model="activity" @change="handleActivity"> Holiday</label>
                            <label><input type="radio" name="activity" value="Leave" x-model="activity" @change="handleActivity"> Leave</label>
                            <label><input type="radio" name="activity" value="Sick" x-model="activity" @change="handleActivity"> Sick</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label>Start Time</label>
                            <input type="time" name="start_time"
                                x-model="startTime"
                                x-effect="watchStartTime()"
                                class="w-full border rounded p-1"
                                :readonly="disableFields">
                        </div>
                        <div>
                            <label>End Time</label>
                            <input type="time" name="end_time"  
                            x-model="endTime" 
                            @input= "isEndTimeManuallyEdited = true; calculateHours();checkTimeValidity()" 
                            class="w-full border rounded p-1">
                            
                        </div>

                    </div>
                    <template x-if="errorMessage">
                        <div class="text-red-500 text-sm mt-2 mb-2" x-text="errorMessage"></div>
                    </template>


                    <div class="mb-4">
                        <label>Remarks</label>
                        <textarea name="remarks" x-model="remarks" class="w-full border rounded p-1"></textarea>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Hours: <span x-text="totalHours"></span></span>
                        <div class="space-x-2">
                            <button type="button" class="text-gray-600" @click="closeModal">Batal</button>
                            <button type="submit"  class="bg-blue-600 text-white px-4 py-1 rounded">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function timesheetModal() {
            return {
                isOpen: false,
                modalDate: '',
                modalDay: '',
                startTime: '',
                endTime: '',
                activity: '',
                remarks: '',
                disableFields: false,
                readonlyRemarks: false,
                totalHours: '',
                isEdit: false, // <--- penanda mode edit
                isEndTimeManuallyEdited: false,
    
                triggerModal(e) {
                    const btn = e.currentTarget;
    
                    this.isEdit = !!btn.dataset.start || !!btn.dataset.end || !!btn.dataset.activity || !!btn.dataset.remarks;
    
                    this.openModal(
                        btn.dataset.date,
                        btn.dataset.day,
                        btn.dataset.start,
                        btn.dataset.end,
                        btn.dataset.activity,
                        btn.dataset.remarks
                    );
                },
    
                openModal(date, day, start = '', end = '', act = '', rem = '') {
                    this.modalDate = date;
                    this.modalDay = day;
                    this.isEndTimeManuallyEdited = false;

    
                    const toHHMM = (time) => {
                        if (!time) return '';
                        const [hh, mm] = time.split(':');
                        return `${hh}:${mm}`;
                    };
    
                    this.startTime = toHHMM(start);
                    this.endTime = toHHMM(end);
                    this.activity = act;
                    this.remarks = rem;
    
                    this.isOpen = true;
    
                    // Sabtu/Minggu langsung diset Holiday
                    if (day === 'Sabtu' || day === 'Minggu' || day === 'Saturday' || day === 'Sunday') {
                        this.startTime = '';
                        this.endTime = '';
                        this.activity = 'Holiday';
                        this.remarks = 'Hari Libur';
                        this.disableFields = true;
                        this.readonlyRemarks = true;
                    } else {
                        this.disableFields = false;
                        this.readonlyRemarks = false;
    
                        // Kalau tambah dan activity Workday, set default jam
                        if (!this.isEdit && this.activity === 'Workday') {
                            this.startTime = '08:00';
                            this.endTime = '17:00';
                        }
                    }
    
                    this.calculateHours();
                },
    
                closeModal() {
                    this.isOpen = false;
                    this.isEndTimeManuallyEdited = false;
                },

                errorMessage: '',

                checkTimeValidity() {
                    if (
                        this.activity === 'Workday' &&
                        this.startTime &&
                        this.endTime &&
                        this.endTime <= this.startTime
                    ) {
                        this.errorMessage = 'End Time harus lebih besar dari Start Time!';
                    } else {
                        this.errorMessage = '';
                    }
                },
    
                watchStartTime() {
                    if (this.activity === 'Workday' && this.startTime && !this.isEndTimeManuallyEdited) {
                        const [hours, minutes] = this.startTime.split(':').map(Number);

                        const startDate = new Date();
                        startDate.setHours(hours);
                        startDate.setMinutes(minutes);

                        const endDate = new Date(startDate.getTime() + 9 * 60 * 60 * 1000);

                        const endHour = String(endDate.getHours()).padStart(2, '0');
                        const endMinute = String(endDate.getMinutes()).padStart(2, '0');

                        this.endTime = `${endHour}:${endMinute}`;
                    }

                    this.calculateHours();
                    this.checkTimeValidity();
                }
                ,
    
                handleActivity() {
                    if (this.modalDay === 'Sabtu' || this.modalDay === 'Minggu' || this.modalDay === 'Saturday' || this.modalDay === 'Sunday') {
                        // Sudah diset saat openModal, tidak perlu ulang
                        return;
                    }
    
                    if (this.activity === 'Holiday') {
                        this.startTime = '';
                        this.endTime = '';
                        this.remarks = 'Hari Libur';
                        this.disableFields = true;
                    } else if (this.activity === 'Leave') {
                        this.startTime = '';
                        this.endTime = '';
                        this.remarks = 'Cuti';
                        this.disableFields = true;
                    } else if (this.activity === 'Sick') {
                        this.startTime = '';
                        this.endTime = '';
                        this.remarks = 'Sakit';
                        this.disableFields = true;
                    } else if (this.activity === 'Workday') {
                        this.disableFields = false;
                        if (!this.isEdit) {
                            this.startTime = '08:00';
                            this.endTime = '17:00';
                        }
                        this.remarks = '';
                    }
    
                    this.calculateHours();
                },

                
    
                calculateHours() {
                    this.checkTimeValidity();


                    if (!this.startTime || !this.endTime || this.activity !== 'Workday') {
                        this.totalHours = '-';
                        return;
                    }
    
                    const [sh, sm] = this.startTime.split(':').map(Number);
                    const [eh, em] = this.endTime.split(':').map(Number);
    
                    let diff = (eh * 60 + em) - (sh * 60 + sm) - 60; // minus 1 jam break
                    let jam = Math.floor(diff / 60);
                    let menit = diff % 60;
    
                    this.totalHours = `${jam} jam ${menit} menit`;
                }
            }
        }
    </script>
    


</x-app-layout>
