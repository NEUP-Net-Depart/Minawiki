@foreach($paginator as $item)

    <li id="{{ $item['id'] }}" class="pointDetailItem">
        <table style="width: 100%; margin: 0;">
            <style>
                .pointDetailItem td {
                    margin: 0;
                    padding: 0;
                    padding-bottom: 2px;
                }
            </style>
            <tr>
                <td>{{ $item['date'] }}</td>
                <td>{{ $item['context'] }}</td>
                <td>{{ $item['result'] }}</td>
            </tr>
        </table>
    </li>
    @endforeach