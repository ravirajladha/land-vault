<x-app-layout>


    <x-header />
    <x-sidebar />

    <div class="content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <div class="page-body">
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/notifications">Notifications</a></li>

                    </ol>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">


                                    <div class="table-responsive">
                                        <table id="example3" class="display" style="min-width: 845px">
                                            {{-- <button type="button" class="btn btn-success mb-2 float-end btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> <i
                                                    class="fas fa-square-plus"></i>&nbsp;Notifications</button> --}}
                                            <span style="float-end btn-sm">
                                                <label for="notificationType">Filter by Notification Type:</label>
                                                <select id="notificationType" class="form-select mb-3"
                                                    style="width:15%;" onchange="filterNotifications()">
                                                    @if ($user && $user->hasPermission('View Compliance Notifications'))
                                                        <option
                                                            value="compliance"{{ request('type') === 'compliance' ? ' selected' : '' }}>
                                                            Compliance</option>
                                                        @if ($user->hasPermission('View Recipient Notifications'))
                                                            <option
                                                                value="document_assignment"{{ request('type') === 'document_assignment' ? ' selected' : '' }}>
                                                                Recipient</option>
                                                        @endif
                                                    @elseif ($user && $user->hasPermission('View Recipient Notifications'))
                                                        <option value="document_assignment" selected>Recipient</option>
                                                    @endif
                                                </select>
                                            </span>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sl. No.</th>
                                                    <th scope="col">Notificattion </th>
                                                    <th scope="col"> Date</th>
                                                    <th scope="col">View Document </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($notifications as $index => $item)
                                                    <tr data-item-id="{{ $item->id }}">
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $item->message }}</td>

                                                        <td>{{ date('d-M-Y H:i:s', strtotime($item->created_at)) }}</td>
                                                        <td>
                                                            @if (isset($item->doc_id))
                                                                <a
                                                                    href="{{ route('documents.review.detail', ['table' => $item->masterDocData->document_type_name, 'id' => $item->doc_id]) }}">
                                                                    <button type="button"
                                                                        class="btn btn-success mb-2  btn-sm">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </a>
                                                            @else
                                                                <span>N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('layouts.footer')


</x-app-layout>
<script type="text/javascript">
    function filterNotifications() {
        var notificationType = document.getElementById('notificationType').value;
        window.location.href = '/notifications?type=' + notificationType;
    }
</script>
