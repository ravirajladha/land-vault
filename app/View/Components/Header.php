<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use App\Models\Master_doc_type;
use App\Models\Alert;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    /**
     * The document types.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $doc_types;
    public $notifications;
    public $notificationsCount;
 
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->doc_types = Master_doc_type::all();

        // Assuming 'View Compliance Notifications' and 'View Document Assignment Notifications' are your permission names
        $user = Auth::user();
        $notificationsQuery = Alert::latest();

        if ($user->hasPermission('View Compliance Notifications') && $user->hasPermission('View Recipient Notifications')) {
            // User has both permissions; no additional filtering is needed
        } elseif ($user->hasPermission('View Compliance Notifications')) {
            $notificationsQuery->whereNotNull('compliance_id');
        } elseif ($user->hasPermission('View Recipient Notifications')) {
            $notificationsQuery->whereNotNull('document_assignment_id');
        } else {
            // User has neither permission; you might want to handle this scenario
            $notificationsQuery = $notificationsQuery->where('id', 0); // No notifications
        }

        $this->notifications = $notificationsQuery->take(5)->get();
        $this->notificationsCount = $notificationsQuery->where('is_read', 0)->count();

        //header name
      
 
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
