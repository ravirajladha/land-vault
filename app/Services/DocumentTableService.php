<?php
// File: app/Services/DocumentTableService.php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Master_doc_type;

class DocumentTableService
{
  
    public function createDocumentType($typeName)
    {
        $type = strtolower(str_replace(' ', '_', $typeName));

        // Check if a DocType with the same name already exists
        $doc_type = Master_doc_type::firstOrCreate(
            ['name' => $type],
            ['created_by' => Auth::user()->id]
        );

        if ($doc_type->wasRecentlyCreated) {
            // Check if the table with the given type name exists
            if (!Schema::hasTable($type)) {
                // Create the table

                Schema::create($type, function (Blueprint $table) {
                    $table->id();
                    $table->text('document_name')->nullable();
                    $table->text('doc_type')->nullable();
                    $table->integer('status')->default(0);
                    $table->integer('doc_id')->nullable();
                    $table->string('pdf_file_path',255)->nullable();
                    $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                    $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                });

                // Generate the model class content
                $modelClassName = ucfirst($type);
                $modelContent = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n\nclass {$modelClassName} extends Model\n{\n    protected \$table = '{$type}';\n}\n";

                // Save the model class to the app/Models directory
                file_put_contents(app_path("Models/{$modelClassName}.php"), $modelContent);

                // Optionally run migrations or perform other tasks
            }
        }
// dd($doc_type);
        return $doc_type;
    }
}
