clear all;  % Clear the workspace
close all;  % Close all figures

addpath("Models");
addpath("Signals");
addpath("Scenarios");

%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Global Variables
%%%%%%%%%%%%%%%%%%%%%%%%%%%
duration = 60;  % Duration of simulation in seconds
variation = 2;  % Amplitude variation of the generated signs form the baseline in %

% Run scenarios
rest(duration, variation);
sleep(duration, variation);
exercise(duration, variation);
hypovolemic_shock(duration, variation);

