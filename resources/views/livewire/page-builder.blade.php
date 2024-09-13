<div x-data="{
    draggingElement: null, // Elemen yang sedang di-drag
    offsetX: 0, // Offset horizontal antara posisi mouse dan elemen
    offsetY: 0, // Offset vertikal antara posisi mouse dan elemen
    gridSize: 20, // Ukuran grid
    snapThreshold: 10, // Batas jarak di mana elemen akan snap ke grid atau elemen lain
    elementOrder: [], // Simpan urutan elemen untuk dikirim ke server saat klik save

    startDrag(event) {
        this.draggingElement = event.target; // Set elemen yang di-drag
        this.offsetX = event.clientX - this.draggingElement.getBoundingClientRect().left;
        this.offsetY = event.clientY - this.draggingElement.getBoundingClientRect().top;
    },

    drag(event) {
        if (this.draggingElement) {
            let left = event.clientX - this.offsetX;
            let top = event.clientY - this.offsetY;

            let snappedLeft = Math.round(left / this.gridSize) * this.gridSize;
            let snappedTop = Math.round(top / this.gridSize) * this.gridSize;

            if (Math.abs(left - snappedLeft) < this.snapThreshold) {
                document.getElementById('vertical-guide').style.left = `${snappedLeft}px`;
                document.getElementById('vertical-guide').style.display = 'block';
            } else {
                document.getElementById('vertical-guide').style.display = 'none';
            }

            if (Math.abs(top - snappedTop) < this.snapThreshold) {
                document.getElementById('horizontal-guide').style.top = `${snappedTop}px`;
                document.getElementById('horizontal-guide').style.display = 'block';
            } else {
                document.getElementById('horizontal-guide').style.display = 'none';
            }

            this.draggingElement.style.position = 'absolute';
            this.draggingElement.style.left = `${snappedLeft}px`;
            this.draggingElement.style.top = `${snappedTop}px`;

            // Update posisi elemen yang sedang di-drag dalam elementOrder
            const id = this.draggingElement.dataset.id;
            this.elementOrder = this.elementOrder.map(item => item.id === id ? { id, left: snappedLeft, top: snappedTop } : item);
            if (!this.elementOrder.find(item => item.id === id)) {
                this.elementOrder.push({ id, left: snappedLeft, top: snappedTop });
            }
        }
    },

    stopDrag() {
        this.draggingElement = null;
        document.getElementById('horizontal-guide').style.display = 'none';
        document.getElementById('vertical-guide').style.display = 'none';
    },

    savePositions() {
        // Kirim data posisi ke Livewire
        @this.call('updateElementPositions', this.elementOrder);
    }
}" 

x-on:mousemove.window="drag($event)"
x-on:mouseup.window="stopDrag()">

    <!-- Container untuk elemen yang bisa dipindahkan -->
    <div class="relative w-full min-h-[90vh] overflow-scroll">
        <!-- Garis panduan horizontal -->
        <div id="horizontal-guide" class="absolute bg-blue-500" style="width: 100%; height: 2px; display: none;"></div>
        <!-- Garis panduan vertikal -->
        <div id="vertical-guide" class="absolute bg-blue-500" style="height: 100%; width: 2px; display: none;"></div>

        <!-- Elemen yang bisa dipindahkan -->
        @foreach($elements as $element)
            <div class="draggable absolute bg-gray-200 p-2" 
                 style="left: {{ $element->x }}px; top: {{ $element->y }}px;" 
                 x-on:mousedown="startDrag($event)" 
                 x-data="{ id: {{ $element->id }} }"
                 data-id="{{ $element->id }}">
                {{ $element->name }}
            </div>
        @endforeach
    </div>

    <!-- Tombol Save -->
    <button @click="savePositions()" class="mt-4 bg-blue-500 text-white p-2 rounded">Save</button>

    <!-- Form atau elemen lain -->
    <div>
        <form class="grid" action="">
            <input type="text" name="" id="">
            <input type="text" name="" id="">
            <input type="text" name="" id="">
        </form>
    </div>
</div>
