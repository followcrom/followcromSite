def process_text(file_path):
    # Read the contents of the file
    with open(file_path, 'r') as file:
        text = file.read()

    # Remove all line breaks from the text
    text = text.replace('\n', ' ')

    # Add two line breaks above and below each <br /><br />
    # text = text.replace('<br /><br />', '\n\n<br /><br />\n\n')

    # Add two line breaks below each "newInc:"
    text = text.replace('newInc:', 'newInc:\n\n')

    # Optionally, save the processed text back to the file or a new file
    output_file = 'processed_' + file_path
    with open(output_file, 'w') as file:
        file.write(text)

    print(f"Text processing complete. Output saved to {output_file}")

# Example usage:
file_path = 'words_split.txt'  # Change this to your file path
process_text(file_path)
