<div class="flex justify-between items-center p-4 bg-gray-100 shadow">
    <!-- Left: Logo -->
    <div class="flex items-center">
        <img src="{{ asset('images/toyoflex.png') }}" class="w-24" alt="Toyoflex Logo">
    </div>

    <!-- Right: Date/Time + User Name -->
    <div class="text-right">
        <div id="topbar-datetime" class="font-medium text-gray-700"></div>
        <div class="font-semibold text-gray-900">{{ auth()->user()->name }}</div>
    </div>
</div>

<!-- JavaScript for real-time date/time -->
<script>
    function updateTime() {
        const dt = new Date();
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const formatted = `${days[dt.getDay()]} | ${dt.getFullYear()}-${('0'+(dt.getMonth()+1)).slice(-2)}-${('0'+dt.getDate()).slice(-2)} | ${('0'+dt.getHours()).slice(-2)}:${('0'+dt.getMinutes()).slice(-2)}:${('0'+dt.getSeconds()).slice(-2)}`;
        document.getElementById('topbar-datetime').innerText = formatted;
    }

    setInterval(updateTime, 1000);
    updateTime();
</script>
