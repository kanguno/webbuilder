<div x-data="{
    draggingElement: null,
    offsetX: 0,
    offsetY: 0,
    gridSize: 20,
    snapThreshold: 10,
    elementOrder: [],

    startDrag(event) {
        if (event.touches) {
            event.preventDefault(); // Mencegah perilaku default
            this.draggingElement = event.target;
            this.offsetX = event.touches[0].clientX - this.draggingElement.getBoundingClientRect().left;
            this.offsetY = event.touches[0].clientY - this.draggingElement.getBoundingClientRect().top;
        } else {
            this.draggingElement = event.target;
            this.offsetX = event.clientX - this.draggingElement.getBoundingClientRect().left;
            this.offsetY = event.clientY - this.draggingElement.getBoundingClientRect().top;
        }
        this.draggingElement.style.transition = 'none'; // Hapus transisi saat drag
    },

    drag(event) {
        if (this.draggingElement) {
            let clientX = event.touches ? event.touches[0].clientX : event.clientX;
            let clientY = event.touches ? event.touches[0].clientY : event.clientY;

            let left = clientX - this.offsetX;
            let top = clientY - this.offsetY;

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

            const id = this.draggingElement.dataset.id;
            this.elementOrder = this.elementOrder.filter(item => item.id !== id);
            this.elementOrder.push({ id, left: snappedLeft, top: snappedTop });
        }
    },

    stopDrag() {
        if (this.draggingElement) {
            this.draggingElement.style.transition = ''; // Kembalikan transisi
            this.draggingElement = null;
            document.getElementById('horizontal-guide').style.display = 'none';
            document.getElementById('vertical-guide').style.display = 'none';
        }
    },

    savePositions() {
        @this.call('updateElementPositions', this.elementOrder)
            .then(response => {
                console.log('Positions saved successfully:', response);
            })
            .catch(error => {
                console.error('Error saving positions:', error);
            });
    },

    init() {
        window.addEventListener('touchstart', (event) => {
            this.startDrag(event); // Menggunakan touch pertama
        }, { passive: false });

        window.addEventListener('touchmove', (event) => {
            this.drag(event); // Menggunakan touch pertama
        }, { passive: false });

        window.addEventListener('touchend', () => {
            this.stopDrag();
        }, { passive: false });

        window.addEventListener('mousedown', (event) => {
            this.startDrag(event);
        });

        window.addEventListener('mousemove', (event) => {
            this.drag(event);
        });

        window.addEventListener('mouseup', () => {
            this.stopDrag();
        });
    }
}" 

x-init="init">

    <div class="relative w-full min-h-[90vh] overflow-scroll">
        <div id="horizontal-guide" class="absolute bg-blue-500" style="width: 100%; height: 2px; display: none;"></div>
        <div id="vertical-guide" class="absolute bg-blue-500" style="height: 100%; width: 2px; display: none;"></div>

        @foreach($elements as $element)
            <div class="draggable absolute bg-gray-200 p-2" 
                 style="left: {{ $element->x }}px; top: {{ $element->y }}px;" 
                 x-data="{ id: {{ $element->id }} }"
                 data-id="{{ $element->id }}">
                {{ $element->name }}
            </div>
        @endforeach
    </div>

    <button @click="savePositions()" class="mt-4 bg-blue-500 text-white p-2 rounded">Save</button>

    <div>
        <form class="grid" action="">
            <input type="text" name="" id="">
            <input type="text" name="" id="">
            <input type="text" name="" id="">
        </form>
    </div>
</div>
