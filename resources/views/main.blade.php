@php /** @var App\Repo[] $offline */ @endphp
@php /** @var App\Repo[] $online */ @endphp

<table>
    @foreach ($offline as $repo)
        <tr>
            <td>{{ $repo->string() }}</td>
            <td>{{ $repo->type() }}</td>
        </tr>
    @endforeach
</table>

<table>
    @foreach ($online as $repo)
        <tr>
            <td>{{ $repo->string() }}</td>
            <td>{{ $repo->type() }}</td>
            <td>{{ $repo->cloneLink() }}</td>
        </tr>
    @endforeach
</table>

<style>
    table, th, td {
        border: 1px black solid;
    }
</style>
