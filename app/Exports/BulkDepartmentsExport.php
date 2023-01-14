namespace App\Exports;

use App\Models\Department;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BulkDepartmentsExport implements FromView
{
    public function view(): View
    {
        return view('export-templates.bulk-departments-upload', [
          'departments'=>Department::all();

        ]);
    }
}
