<div>
    <style>
        .draggable {
            width: 100px;
            height: 50px;
            background: #ddd;
            border: 1px solid #aaa;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: move;
            position: absolute;
            z-index: 1000;
            transition: transform 0.2s;
            transform-origin: 0 0;
        }

        .controls {
            margin-top: 20px;
        }

        .controls input {
            margin-right: 10px;
        }
    </style>
    <div class="flex w-full justify-between bg-gray-50 p-5 gap-28">

    <div class="halaman p-5 bg-white shadow-md w-full">
        @foreach($elements as $item)
                <div wire:click="setCurrentElement({{$item->id}})"
                    class="draggable"
                    id="item-{{ $item->id }}"
                    data-id="{{ $item->id }}"
                    data-x="{{ $item->x }}"
                    data-y="{{ $item->y }}"
                    data-width="{{ $item->width }}"
                    data-height="{{ $item->height }}"
                    data-rotation="{{ $item->rotation }}"
                    >
                    {{ $item->name }}
                </div>
            @endforeach
    </div>
   
    <div class="controls grid">

        <label for="width">ID:</label>
        <input wire:model="id"type="number" id="id" min="1">

        <label for="width">Width:</label>
        <input wiremodel="width" type="number" id="width" value="100" min="1">
        
        <label for="height">Height:</label>
        <input type="number" id="height" value="50" min="1">
        
        <label for="rotation">Rotation:</label>
        <input type="number" id="rotation" value="0" step="1">
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const updateElementStyle = (element) => {
                if (element) {
                    const width = document.getElementById('width').value;
                    const height = document.getElementById('height').value;
                    const rotation = document.getElementById('rotation').value;
                    
                    element.style.width = `${width}px`;
                    element.style.height = `${height}px`;
                    element.style.transform = `rotate(${rotation}deg)`;
                }
            };

            const makeDraggable = (element) => {
                let isDragging = false;
                let offsetX, offsetY;

                element.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    const rect = element.getBoundingClientRect();
                    offsetX = e.clientX - rect.left;
                    offsetY = e.clientY - rect.top;

                    const onMouseMove = (e) => {
                        if (isDragging) {
                            const x = e.pageX - offsetX;
                            const y = e.pageY - offsetY;

                            element.style.left = `${x}px`;
                            element.style.top = `${y}px`;
                        }
                    };

                    const onMouseUp = () => {
                        isDragging = false;
                        document.removeEventListener('mousemove', onMouseMove);
                        document.removeEventListener('mouseup', onMouseUp);
                    };

                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                });
            };

            document.querySelectorAll('.draggable').forEach(element => {
                makeDraggable(element);
                updateElementStyle(element);
            });

            document.getElementById('width').addEventListener('input', () => {
                document.querySelectorAll('.draggable').forEach(element => updateElementStyle(element));
            });
            document.getElementById('height').addEventListener('input', () => {
                document.querySelectorAll('.draggable').forEach(element => updateElementStyle(element));
            });
            document.getElementById('rotation').addEventListener('input', () => {
                document.querySelectorAll('.draggable').forEach(element => updateElementStyle(element));
            });
        });
    </script>
</div>
