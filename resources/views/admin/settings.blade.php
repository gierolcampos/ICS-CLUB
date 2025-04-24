@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Settings</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="club_name">Club Name</label>
                            <input type="text" class="form-control @error('club_name') is-invalid @enderror" 
                                   id="club_name" name="club_name" value="{{ old('club_name', $settings['club_name']) }}">
                            @error('club_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                   id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}">
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone">Timezone</label>
                            <select class="form-control @error('timezone') is-invalid @enderror" 
                                    id="timezone" name="timezone">
                                <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ $settings['timezone'] == 'America/New_York' ? 'selected' : '' }}>Eastern Time (US & Canada)</option>
                                <option value="America/Chicago" {{ $settings['timezone'] == 'America/Chicago' ? 'selected' : '' }}>Central Time (US & Canada)</option>
                                <option value="America/Denver" {{ $settings['timezone'] == 'America/Denver' ? 'selected' : '' }}>Mountain Time (US & Canada)</option>
                                <option value="America/Los_Angeles" {{ $settings['timezone'] == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (US & Canada)</option>
                                <option value="Europe/London" {{ $settings['timezone'] == 'Europe/London' ? 'selected' : '' }}>London</option>
                                <option value="Europe/Paris" {{ $settings['timezone'] == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                <option value="Asia/Tokyo" {{ $settings['timezone'] == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                            </select>
                            @error('timezone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" 
                                       id="enable_notifications" name="enable_notifications" value="1"
                                       {{ $settings['enable_notifications'] ? 'checked' : '' }}>
                                <label class="custom-control-label" for="enable_notifications">Enable Email Notifications</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" 
                                       id="auto_approve_members" name="auto_approve_members" value="1"
                                       {{ $settings['auto_approve_members'] ? 'checked' : '' }}>
                                <label class="custom-control-label" for="auto_approve_members">Auto-approve New Members</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" 
                                       id="allow_guest_rsvp" name="allow_guest_rsvp" value="1"
                                       {{ $settings['allow_guest_rsvp'] ? 'checked' : '' }}>
                                <label class="custom-control-label" for="allow_guest_rsvp">Allow Guest RSVP for Events</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 