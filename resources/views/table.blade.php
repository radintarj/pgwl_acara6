@extends('layouts.template')

@section('styles')
<style>
    body {
        margin: 0;
        padding: 0;
    }
</style>
    
@endsection


@section('content')

    <div class="card">
        <div class="card-header">
            <h3>Tabel Data</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Bundaran UGM</td>
                        <td>Jalan Pancasila</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Mirota</td>
                        <td>Jalan Kaliurang</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Vokasi</td>
                        <td>Jalan Kaliurang</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Mcd</td>
                        <td>Jalan Kaliurang</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Tempo Gelato</td>
                        <td>Jalan Kaliurang</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')

@endsection