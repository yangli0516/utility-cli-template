# Utility CLI Template

This is a template project which can be used to create utility tools.
It provides the project structure as well as some commonly used utility
classes.

## Add Commands

The template uses Symfony's console component to create console commands.
To add a command, create the command class under `src/Command` folder
and add the command in the `utility` file at the project root.

The template by default provides an example command called _Greetings_.

## Input & Output Directories

The template provides two directories for input & output files: `input`
and `output`. Any data file used as input can sit under the `input` folder.
At the same time, any output file can be output to the `output` folder.

There are two global constants called `DATA_INPUT_DIR` and `DATA_OUTPUT_DIR`
which can be easily used to get the path of these two directories.
